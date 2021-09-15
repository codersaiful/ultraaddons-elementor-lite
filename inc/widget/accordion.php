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
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;


class Accordion extends Base{
    
    /**
     * Get your widget name
     *
     * Retrieve image accordion widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'toggle', 'accordion' ];
    }

    /**
     * Register image accordion widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        // For General Section
        $this->content_general_contents_controls();
        $this->content_tab_content_controls();
        $this->style_tab_general_controls();
        $this->style_tab_title_controls();
        $this->style_tab_content_controls();
        // $this->accordion_action_icon_style_section();
        
    }

    /**
     * Render image accordion widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        extract($settings);
        // var_dump($ua_img_accordion_items);
        // var_dump($_ua_accordions_skin, $settings);
        $_ua_accordions_skin  =  !empty( $_ua_accordions_skin ) ? $_ua_accordions_skin : '_skin_1';
        switch ($_ua_accordions_skin) {
            case '_skin_1':
                 $this->_ua_accordions_style_one();
                break;
            default:
                $this->_ua_accordions_style_one();
                break;
        }
        ?>

        <?php
    }

    //Layout One
    protected function _ua_accordions_style_one(){
        $settings = $this->get_settings_for_display();
        extract($settings);
        $migrated = isset( $__fa4_migrated['_ua_accordion_selected_icon'] );

        if (  !empty( $icon ) && ! Icons_Manager::is_migration_allowed() ) {
        
            $settings['icon'] = 'fas fa-angle-up';
            $settings['icon_active'] = 'fas fa-angle-down';
        }


        $is_new = empty( $icon ) && Icons_Manager::is_migration_allowed();
        $has_icon = ( ! $is_new || ! empty( $_ua_accordion_selected_icon['value'] ) );
        
        $this->add_render_attribute(
            'ua_accordion_wrapper',
            [
                'id' => "ua-advance-accordions-{$this->get_id()}",
                'class' => ['ua-advance-accordions ua_accordion_container', $_ua_accordions_skin ],
                'data-Accordionid' => $this->get_id(),
            ]
        );
        
        $has_accordions = ! empty( $_ua_accordions_list );
        $id_int = substr( $this->get_id_int(), 0, 4 );
        ?>
        <?php if ($has_accordions): ?>
        <div <?php echo $this->get_render_attribute_string('ua_accordion_wrapper'); ?> >
            <div class="ua_accordion">
                <?php
                $i = 1;
                foreach ( $_ua_accordions_list as $index => $item ) :
                    
                    $tab_count = $index + 1;

                    
                    $has_title_text = ! empty( $item['_ua_accordions_title'] );

                    $has_description_text = ! empty( $item['_ua_accordions_description_text'] );

                    $has_image = ! empty( $item['_ua_accordions_image']['url'] );

                    $tab_title_setting_key = $this->get_repeater_setting_key( '_ua_accordions_title', '_ua_accordions_list', $index );

                    $tab_content_setting_key = $this->get_repeater_setting_key( '_ua_accordions_description_text', '_ua_accordions_list', $index );

                    $icon_tag = '';
                    if ( ! empty( $item['_ua_accordions_link']['url'] ) ) {
                        $icon_tag = 'a';
                        $this->add_link_attributes( '_ua_accordions_link', $item['_ua_accordions_link'] );
                    }
                    $link_attributes = $this->get_render_attribute_string( '_ua_accordions_link' );


                    $title_active_class = '';
                    $content_active_class = '';

                    if ($item['_ua_accordions_show_as_default'] == 'yes') {
                        $title_active_class = 'ua-active-default ua-active';
                        $content_active_class = 'ua-active-default ua-active';
                    }

                    $this->add_render_attribute( $tab_title_setting_key, [
                        'id' => 'accordion-tab-title-' . $id_int . $tab_count,
                        'class' => [ 'ua_accordion_item_title ua-accordion-title', $title_active_class ],
                        'data-speed' => 400,
                    ] );

                    $this->add_render_attribute( $tab_content_setting_key, [
                        'id' => 'ua-tab-content-' . $id_int . $tab_count,
                        'class' => [ 'ua_accordion_panel', 'ua-accordion-content-wrapper', $content_active_class ],
                    ] );

                    ?>
                    <div class="ua_accordion_item ua_accordion_style_08 ua-accordion-wrapper">
                        <div <?php echo $this->get_render_attribute_string( $tab_title_setting_key ); ?>>
                            <?php if ( $has_title_text ) : ?>
                                <<?php echo esc_html( ultraaddons_title_tag( $item['_ua_accordions_title_size'] ) ); ?> class="ua_accordion_title ua-accordions-title">
                                    <?php echo do_shortcode($item['_ua_accordions_title']); ?>
                                </<?php echo esc_html( ultraaddons_title_tag( $item['_ua_accordions_title_size'] ) ); ?>>
                            <?php endif; ?>
                            <div class="ua-icon">
                                <?php 
                                if ( $_ua_accordions_icon_show === 'yes' ) {
                                    if( $_ua_accordions_icon_type == 'icon' ){
                                        if ( $is_new || $migrated ) { ?>
                                            <span class="ua-accordion_icon" aria-hidden="true">
                                                <span class="ua-accordion-icon-closed"><?php Icons_Manager::render_icon( $_ua_accordion_selected_icon ); ?></span>

                                                <span class="ua-accordion-icon-opend"><?php Icons_Manager::render_icon( $selected_active_icon ); ?></span>
                                            </span>
                                    <?php }
                                    }elseif( $_ua_accordions_icon_type == 'image' ){ ?>
                                        <span class="ua-accordion_icon" aria-hidden="true">
                                            <span class="ua-accordion-icon-closed">
                                                <img src="<?php echo esc_url( $_ua_accordions_icon_image['url'] ); ?>" alt="closed Icon">
                                            </span>
                                            <span class="ua-accordion-icon-opend">
                                                <img src="<?php echo esc_url( $_ua_accordions_active_image['url'] ); ?>" alt="Opend Icon">
                                            </span>
                                        </span>

                                <?php }
                                    
                                }

                                ?>
                            </div>
                        </div>
                        <div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
                            <div class="ua_accordion_inner">
                                <?php if ( 'yes' == $item['_ua_accordions_image_show'] ) : ?>
                                    <?php if ( $has_image ): ?>
                                    <div class="ua_accordion_thumb">
                                        <a <?php echo $link_attributes; ?> >
                                        <img src="<?php echo esc_url($item['_ua_accordions_image']['url']); ?>" alt="#" class="ua_img_res">
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="ua_accordion_inner_content">
                                    <?php if ( $has_description_text ) : ?>
                                    <p class="ua_desc">
                                        <?php echo do_shortcode( $item['_ua_accordions_description_text'] ); ?>
                                    </p>
                                    <?php endif; ?>
                                    
                                    <?php if ( 'yes' == $item['_ua_accordions_button_show'] ) : ?>
                                        <a <?php echo $link_attributes; ?> class="ua_cu_btn btn_2 ua-accordion-button">
                                            <?php echo ultraaddons_addons_kses( $item['_ua_accordions_button_text'] ); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $i++; 
                endforeach; ?>
            </div>
        </div>
    <?php endif;
    }

    protected function content_general_contents_controls(){

        $this->start_controls_section(
			'_ua_accordions_preset_section',
			[
				'label' => __( 'Preset', 'ultraaddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
		    '_ua_accordions_skin',
		    [
			    'label' => esc_html__( 'Design Format', 'ultraaddons' ),
			    'type' => Controls_Manager::SELECT,
			    'label_block' => false,
			    'options'   => [
				    '_skin_1' => 'Style 01',
			    ],
			    'default' => '_skin_1'
		    ]
	    );


        $this->add_control(
            '_ua_accordions_icon_show',
            [
                'label'         => esc_html__('Enable Icon', 'ultraaddons'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'return_value'  => 'yes',
            ]
        );
        $this->add_control(
            '_ua_accordions_icon_type',
            [   
                'label' => esc_html__('Icon Type', 'ultraaddons'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title'     => esc_html__('None', 'ultraaddons'),
                        'icon'      => 'eicon-ban',
                    ],
                    'icon' => [
                        'title'     => esc_html__('Icon', 'ultraaddons'),
                        'icon'      => 'eicon-star',
                    ],
                    'image' => [
                        'title'     => esc_html__('Image', 'ultraaddons'),
                        'icon'      => 'eicon-featured-image',
                    ],
                ],
                'default'   => 'icon',
                'condition' => [
                    '_ua_accordions_icon_show'  => [ 'yes' ],
                ],
            ]
        );
        $this->add_control(
            '_ua_accordion_selected_icon',
            [
                'label'             => __( 'Icon', 'ultraaddons' ),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'icon',
                'default'           => [
                    'value'     => 'fas fa-angle-down',
                    'library'   => 'fa-solid',
                ],
                'recommended'       => [
                    'fa-solid'      => [
                        'chevron-down',
                        'angle-down',
                        'angle-double-down',
                        'caret-down',
                        'caret-square-down',
                    ],
                    'fa-regular'    => [
                        'caret-square-down',
                    ],
                ],
                'condition' => [
                    '_ua_accordions_icon_show'  => [ 'yes' ],
                    '_ua_accordions_icon_type'  => [ 'icon' ],
                ],
            ]
        );
        
       
         $this->add_control(
             '_ua_accordions_icon_image',
             [   
                 'label'    => esc_html__('Image', 'ultraaddons'),
                 'type'     => Controls_Manager::MEDIA,
                 'default'  => [
                     'url'  => '',
                 ],
                 'condition' => [
                    '_ua_accordions_icon_show'  => [ 'yes' ],
                    '_ua_accordions_icon_type'  => [ 'image' ],
                ],
             ]
         );


        $this->add_control(
            'selected_active_icon',
            [
                'label'             => __( 'Icon', 'ultraaddons' ),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'icon_active',
                'default'       => [
                    'value'     => 'fas fa-angle-up',
                    'library'   => 'fa-solid',
                ],
                'recommended'   => [
                    'fa-solid'  => [
                        'chevron-up',
                        'angle-up',
                        'angle-double-up',
                        'caret-up',
                        'caret-square-up',
                    ],
                    'fa-regular'=> [
                        'caret-square-up',
                    ],
                ],
                
                'condition' => [
                    '_ua_accordions_icon_show'  => [ 'yes' ],
                    '_ua_accordions_icon_type'  => 'icon',
                    '_ua_accordions_icon_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
             '_ua_accordions_active_image',
             [   
                 'label' => esc_html__('Image', 'ultraaddons'),
                 'type' => Controls_Manager::MEDIA,
                 'default' => [
                     'url' => '',
                 ],
                 'condition' => [
                    '_ua_accordions_icon_show'  => [ 'yes' ],
                    '_ua_accordions_icon_type'  => [ 'image' ],
                    '_ua_accordions_icon_type!' => 'none',
                ],
             ]
         );

		$this->end_controls_section();

    }

    protected function content_tab_content_controls(){

        $this->start_controls_section(
            '_ua_accordions_content_settings',
            [
              'label' => esc_html__( 'Content', 'ultraaddons' ),
              'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'_ua_accordions_title',
			[
				'label' => __( 'Accordion Title','ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Accordion Title','ultraaddons' ),
				'placeholder' => __( 'Enter your title','ultraaddons' ),
				'label_block' => true,
			]
		);
		

		$repeater->add_control(
			'_ua_accordions_show_as_default',
			[
                'label' => __('Set as Default','ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
			]
		);
		$repeater->add_control(
			'_ua_accordions_content_heading',
			[
				'label'     => __( 'Content','ultraaddons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'_ua_accordions_description_text',
			[
				'label' => 'Description',
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Choose your training and register for free. If you are a freelancer, the courses are entirely taken care of, you have nothing to pay and no money to advance.','ultraaddons' ),
				'placeholder' => __( 'Enter your description','ultraaddons' ),
				'show_label' => true,
                'rows' => 10,
			]
		);
        $repeater->add_control(
            '_ua_accordions_image_show',
            [
                'label' => esc_html__('Enable Image','ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );
        $repeater->add_control(
            '_ua_accordions_image', [
                'label'      => __('Image','ultraaddons'),
                'type'       => Controls_Manager::MEDIA,
                'default'    => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'show_label' => false,
                'condition' => [
                    '_ua_accordions_image_show' => [ 'yes' ],
                ],
            ]
        );
		$repeater->add_control(
            '_ua_accordions_button_show',
            [
                'label' => esc_html__('Enable Button','ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );
		$repeater->add_control(
			'_ua_accordions_button_text',
			[
				'label' => __( 'Button','ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Work With Us','ultraaddons' ),
				'placeholder' => __( 'Enter your text','ultraaddons' ),
				'label_block' => true,
                'condition' => [
                    '_ua_accordions_button_show' => [ 'yes' ],
                ],
			]
		);
		$repeater->add_control(
			'_ua_accordions_link',
			[
				'label' => __( 'Link','ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com','ultraaddons' ),
                'condition' => [
                    '_ua_accordions_button_show' => [ 'yes' ],
                ],
			]
		);
        $repeater->add_control(
            '_ua_accordions_title_size',
            [
                'label' => __( 'Title HTML Tag','ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1','ultraaddons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2','ultraaddons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3','ultraaddons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4','ultraaddons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5','ultraaddons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6','ultraaddons' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'p'  => [
                        'title' => __( 'P','ultraaddons' ),
                        'icon' => 'eicon-editor-paragraph'
                    ],
                ],
                'default' => 'h3',
                'toggle' => false,
            ]
        );

		$this->add_control(
            '_ua_accordions_list',
            [
                'label'       => __('Accordions','ultraaddons'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['_ua_accordions_title' => esc_html__('Accordion Title 1','ultraaddons')],
                    ['_ua_accordions_title' => esc_html__('Accordion Title 2','ultraaddons')],
                    ['_ua_accordions_title' => esc_html__('Accordion Title 3','ultraaddons')],
                    ['_ua_accordions_title' => esc_html__('Accordion Title 4','ultraaddons')],
                ],
                'title_field' => '{{{ _ua_accordions_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function style_tab_general_controls(){

        $this->start_controls_section(
            '_ua_accordions_style_general',
            [
                'label' => esc_html__('General', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            '_ua_accordions_padding',
            [
                'label' => esc_html__('Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            '_ua_accordions_margin',
            [
                'label' => esc_html__('Margin Bottom', 'ultraaddons'),
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
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_item_border',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => '_ua_accordions_box_shadow',
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper',
            ]
        );
        $this->end_controls_section();

    }

    protected function style_tab_title_controls(){
        $this->start_controls_section(
            '_ua_accordions_title_style_settings',
            [
                'label' => esc_html__('Title', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_accordions_title_typography',
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordions-title',
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_title_padding',
            [
                'label' => esc_html__('Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_icon_size',
            [
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
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-icon img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_icon_gap',
            [
                'label' => esc_html__('Icon Gap', 'ultraaddons'),
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
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-icon i' => 'margin: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-icon img' => 'margin: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-icon svg' => 'margin: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('_ua_accordions_header_tabs');

        $this->start_controls_tab('_ua_accordions_header_normal', 
        	['label' => esc_html__('Normal', 'ultraaddons')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_accordions_bgtype',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title'
            ]
        );
        $this->add_control(
            '_ua_accordions_text_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordions-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_accordions_icon_color',
            [
                'label' => esc_html__('Icon Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title .ua-icon i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_accordions_icon_show' => [ 'yes' ],
                    '_ua_accordions_icon_type' => 'icon',
                ],
            ]

        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_border',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title',
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_border_radius',
            [
                'label' => esc_html__('Border Radius', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('_ua_accordions_header_hover', 
        	['label' => esc_html__('Hover', 'ultraaddons')]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_accordions_bgtype_hover',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title:hover'
            ]
        );
        $this->add_control(
            '_ua_accordions_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordions-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_accordions_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title:hover .ua-icon i' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    '_ua_accordions_icon_show' => [ 'yes' ],
                    '_ua_accordions_icon_type' => 'icon',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_border_hover',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title:hover',
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('_ua_accordions_header_active', 
        	['label' => esc_html__('Active', 'ultraaddons')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_accordions_bgtype_active',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title.ua-active'
            ]
        );
        $this->add_control(
            '_ua_accordions_text_color_active',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title.ua-active .ua-accordions-title' => 'color: {{VALUE}};',
                    
                ],
            ]
        );
        $this->add_control(
            '_ua_accordions_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title.ua-active .ua-icon i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_accordions_icon_show' => [ 'yes' ],
                    '_ua_accordions_icon_type' => 'icon',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_border_active',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title.ua-active',
            ]
        );

        $this->add_responsive_control(
            '_ua_accordions_radius_active',
            [
                'label' => esc_html__('Border Radius', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-title.ua-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function style_tab_content_controls(){

        $this->start_controls_section(
            '_ua_accordions_content_style_settings',
            [
                'label' => esc_html__('Content', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            '_ua_accordions_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ua_accordions_content_bgtype',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper'
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_content_padding',
            [
                'label' => esc_html__('Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_content_border',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => '_ua_accordions_content_shadow',
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper',
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            '_ua_accordions_content_heading',
            [
                'label' => __( 'Description', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );
        $this->add_control(
            '_ua_accordions_content_text_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_accordions_content_typography',
                'selector' => 
	                '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content p',
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_content_align',
            [
                'label' => __( 'Alignment', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'center' => [
                        'title' => __( 'Center', 'ultraaddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-start' => [
                        'title' => __( 'Top', 'ultraaddons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => __( 'Bottom', 'ultraaddons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            '_ua_accordions_button_heading',
            [
                'label' => __( 'Button', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );
        $this->start_controls_tabs( '_ua_accordion_button_tabs' );

        $this->start_controls_tab( '_ua_accordion_normal_style',
            [ 
                'label' => esc_html__( 'Style', 'ultraaddons')
            ] 
        );

         $this->add_control(
            '_ua_accordions_button_text_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_accordions_button_bg_color',
            [
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_border_button',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( '_ua_accordion_button_hover',
            [ 
                'label' => esc_html__( 'Hover', 'ultraaddons')
            ] 
        );
        
        $this->add_control(
            '_ua_accordions_button_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_accordions_button_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => '_ua_accordions_border_button_hover',
                'label' => esc_html__('Border', 'ultraaddons'),
                'selector' => '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button:hover',
            ]
        );
        $this->end_controls_tab();
                
        $this->end_controls_tabs();
       
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_accordions_button_typography',
                'selector' => 
	                '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button',
	          
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_button_padding',
            [
                'label' => esc_html__('Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_accordions_button_margin',
            [
                'label' => esc_html__('Margin', 'ultraaddons'),
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
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        

        $this->add_responsive_control(
            '_ua_accordions_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-advance-accordions .ua-accordion-wrapper .ua-accordion-content-wrapper .ua_accordion_inner_content .ua-accordion-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

}
