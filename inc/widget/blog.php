<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
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


class Blog extends Base{
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
       
        //For Blog Widget
        $name           = 'imagesloaded.pkgd.min';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/imagesloaded.pkgd.min.js';
        $dependency     =  ['jquery','elementor-frontend'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        //For Blog Widget
        $name           = 'isotope.pkgd.min';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/isotope.pkgd.min.js';
        $dependency     =  ['jquery','elementor-frontend'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        //For Blog Widget
        $name           = 'packery-mode.pkgd.min';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/packery-mode.pkgd.min.js';
        $dependency     =  ['jquery','elementor-frontend'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
        
        
        //Naming of Args for Masonary
        //For Blog Widget
        $name           = 'masonry_grid';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/masonry_grid.js';
        $dependency     =  ['jquery','elementor-frontend'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
        
    }
    
    public function get_style_depends() {
        return ['imagesloaded.pkgd.min','packery-mode.pkgd.min','isotope.pkgd.min', 'masonry_grid'];
    }
    
    
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
        return [ 'ultraaddons', 'blog', 'post' ];
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
        $this->ua_register_blog_layout();
        $this->ua_blog_layout_types();
        $this->ua_content_tab_content_controls();
        $this->ua_style_tab_general_section();
        $this->ua_style_tab_thumb_section();
        $this->ua_style_tab_title_section();
        $this->ua_style_tab_content_section();
        $this->ua_style_tab_category_section();
        $this->ua_style_tab_author_section();
        $this->ua_style_tab_date_section();
        
    }

    /**
     * Render image accordion widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    // protected function render() {
    //     $settings = $this->get_settings_for_display();
    //     extract($settings);
    //     // var_dump($ua_img_accordion_items);
    //     // var_dump($_ua_accordions_skin, $settings);
        
    // }

    

    protected function ua_register_blog_layout(){
        $this->start_controls_section(
            '_ua_blog_layout_section',
            [
                'label' => esc_html__('Layout', 'ultraaddons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            '_ua_blog_skin',
            [
                'label' => esc_html__( 'Design Format', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options'   => [
                    '_skin_1' => 'Style 01',
                    '_skin_2' => 'Style 02',
                    '_skin_3' => 'Style 03',
                    '_skin_4' => 'Style 04',
                ],
                'default' => '_skin_1'
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => '_ua_thumbnail_size',
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            '_ua_blog_columns',
            [
                'label' => __( 'Columns', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4',
                    '5' => '5',
                    '2' => '6',
                ],
                'condition' => [
                    '_ua_blog_skin!' => ['_skin_1', '_skin_4'],
                ],
            ]
        );

        $this->add_control(
            '_ua_show_title',
            [
                'label'     => __('Title', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __('Show', 'ultraaddons'),
                'label_off' => __('Hide', 'ultraaddons'),
                'default'   => 'yes',
            ]
        );

        $this->add_control(
            '_ua_show_excerpt',
            [
                'label'     => __('Excerpt', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __('Show', 'ultraaddons'),
                'label_off' => __('Hide', 'ultraaddons'),
                'default'   => 'yes',
                'separator' => 'before',
                'condition' => [
                    '_ua_blog_skin' => ['_skin_3'],
                ],
            ]
        );

        $this->add_control(
            '_ua_title_tag',
            [
                'label' => __( 'Title HTML Tag', 'ultraaddons' ),
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
                'default' => 'h3',
                'toggle' => false,
                'condition' => [
                    '_ua_show_title' => ['yes'],
                ],

            ]
        );
        $this->add_control(
            '_ua_show_category',
            [
                'label'     => __('Category', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'label_off' => __('Hide', 'ultraaddons'),
                'label_on'  => __('Show', 'ultraaddons'),
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_1', '_skin_2', '_skin_3'],
                ],
            ]
        );
        $this->add_control(
            '_ua_show_tag',
            [
                'label'     => __('Tags', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'label_off' => __('Hide', 'ultraaddons'),
                'label_on'  => __('Show', 'ultraaddons'),
                'condition' => [
                    '_ua_blog_skin' => ['_skin_2'],
                ]
            ]
        );
        
        $this->add_control(
            '_ua_show_thumb',
            [
                'label'     => __('Show Image', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'label_off' => __('Hide', 'ultraaddons'),
                'label_on'  => __('Show', 'ultraaddons'),
            ]
        );
        $this->add_control(
            '_ua_show_author',
            [
                'label'     => __('Show Author', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'label_off' => __('Hide', 'ultraaddons'),
                'label_on'  => __('Show', 'ultraaddons'),
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_2', '_skin_3', '_skin_4'],
                ],
            ]
        );

        $this->add_control(
            '_ua_show_date',
            [
                'label'     => __('Show Date', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'label_off' => __('Hide', 'ultraaddons'),
                'label_on'  => __('Show', 'ultraaddons'),
            ]
        );
    

        $this->add_control(
            '_ua_show_read_more',
            [
                'label'     => __('Read More', 'ultraaddons'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __('Show', 'ultraaddons'),
                'label_off' => __('Hide', 'ultraaddons'),
                'default'   => 'yes',
                'separator' => 'before',
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_4'],
                ],
            ]
        );

        $this->add_control(
            '_ua_read_more_text',
            [
                'label'     => __('Read More Text', 'ultraaddons'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Learn More »', 'ultraaddons'),
                'condition' => [
                    '_ua_show_read_more' => ['yes'],
                    '_ua_blog_skin' => ['_skin_4'],
                ] 
            ]
        );

        $this->add_control(
            '_ua_open_new_tab',
            [
                'label' => __( 'Open in new window', 'ultraaddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'ultraaddons' ),
                'label_off' => __( 'No', 'ultraaddons' ),
                'default' => 'no',
                'render_type' => 'none',
            ]
        );

        $this->end_controls_section();
    }

    protected function ua_blog_layout_types(){
        $this->start_controls_section(
            '_ua_blog_masonary_section',
            [
                'label' => esc_html__('Masonary Layout (Skin 1)', 'ultraaddons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    '_ua_blog_skin' => ['_skin_1'],
                ]
            ]
        );
        $this->add_control(
            '_ua_masonary_type',
            [
                'label'   => __('Masonary Type', 'ultraaddons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'metro',
                'options' => [
                    'metro'   => __('Metro', 'ultraaddons'),
                    'masonry'       => __('Masonry', 'ultraaddons'),
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_zigzag_height',
            [
                'label'     => esc_html__( 'Zigzag Height', 'ultraaddons' ),
                'type'      => Controls_Manager::NUMBER,
                'step'      => 1,
            ] 
        );

        $this->add_control(
            '_ua_zigzag_reversed',
            [
                'label'     => esc_html__( 'Zigzag Reversed?', 'ultraaddons' ),
                'type'      => Controls_Manager::SWITCHER,   
            ] 
        );


        $this->add_control(
            '_ua_metro_image_size_width',
            [
                'label'     => esc_html__( 'Image Size', 'ultraaddons' ),
                'type'      => Controls_Manager::NUMBER,
                'step'      => 1,
                'default'   => 480,
            ]
        );

        $this->add_control(
            '_ua_metro_image_ratio',
            [
                'label'     => esc_html__( 'Image Ratio', 'ultraaddons' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 2,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default'   => [
                    'size' => 1,
                ],
                
            ]
        );

        $this->add_responsive_control(
            '_ua_grid_columns',
            [
                'label'          => esc_html__( 'Columns', 'ultraaddons' ),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 12,
                'step'           => 1,
                'default'        => 4,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            '_ua_grid_gutter',
            [
                'label'   => esc_html__( 'Gutter', 'ultraaddons' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'default' => '',
            ]
        );

        $layout_repeater = new Repeater();

        $layout_repeater->add_control(
            '_ua_size',
            [
                'label'   => esc_html__( 'Item Size', 'ultraaddons' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1:1',
                'options' => ultraaddons_get_grid_metro_size(),
            ]
        );

        $this->add_control(
            '_ua_grid_metro_layout',
            [
                'label'       => esc_html__( 'Metro Layout', 'ultraaddons' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $layout_repeater->get_controls(),
                'default'     => [
                    [ 'size' => '2:2' ],
                    [ 'size' => '1:1' ],
                    [ 'size' => '1:1' ],
                    [ 'size' => '2:2' ],                    
                    [ 'size' => '1:1' ],                    
                    [ 'size' => '1:1' ],                    
                ],
                'title_field' => '{{{ size }}}',
            ]
        );
        $this->end_controls_section();

        //for skin 4
        $this->start_controls_section(
            '_ua_blog_masonary_four_section',
            [
                'label' => esc_html__('Masonary Layout (Skin 4)', 'ultraaddons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    '_ua_blog_skin' => ['_skin_4'],
                ]
            ]
        );
        $this->add_control(
            '_ua_masonary_type_four',
            [
                'label'   => __('Masonary Type', 'ultraaddons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'metro'   => __('Metro', 'ultraaddons'),
                    'masonry'       => __('Masonry', 'ultraaddons'),
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_zigzag_height_four',
            [
                'label'     => esc_html__( 'Zigzag Height', 'ultraaddons' ),
                'type'      => Controls_Manager::NUMBER,
                'step'      => 1, 
            ] 
        );

        $this->add_control(
            '_ua_zigzag_reversed_four',
            [
                'label'     => esc_html__( 'Zigzag Reversed?', 'ultraaddons' ),
                'type'      => Controls_Manager::SWITCHER,
                
            ] 
        );


        $this->add_control(
            '_ua_metro_image_size_width_four',
            [
                'label'     => esc_html__( 'Image Size', 'ultraaddons' ),
                'type'      => Controls_Manager::NUMBER,
                'step'      => 1,
                'default'   => 480,
            ]
        );

        $this->add_control(
            '_ua_metro_image_ratio_four',
            [
                'label'     => esc_html__( 'Image Ratio', 'ultraaddons' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 2,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'default'   => [
                    'size' => null,
                ],
                
            ]
        );

        $this->add_responsive_control(
            '_ua_grid_columns_four',
            [
                'label'          => esc_html__( 'Columns', 'ultraaddons' ),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 12,
                'step'           => 1,
                'default'        => 2,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            '_ua_grid_gutter_four',
            [
                'label'   => esc_html__( 'Gutter', 'ultraaddons' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'default' => 30,
            ]
        );
        $this->end_controls_section();
    }

    protected function ua_content_tab_content_controls(){

        $this->start_controls_section(
            '_ua_blog_query_section',
            [
                'label' => esc_html__('Query', 'ultraaddons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );        

        $this->add_control(
            '_ua_post_type',
            [
                'label' => __( 'Source', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT,
                'options' => ultraaddons_get_post_types( [],[ 'elementor_library', 'attachment' ] ),
                'default' => 'post',
            ]
        );

        $this->add_control(
            '_ua_posts_per_page', [
                'label'       => esc_html__('Posts Per Page', 'ultraaddons'),
                'type'        => Controls_Manager::NUMBER,
                'placeholder' => esc_html__('Enter Number', 'ultraaddons'),
                'default'     => '6',
            ]
        );

        $this->add_control(
            '_ua_order_by',
            [
                'label'   => __('Order By', 'ultraaddons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'modified'   => __('Modified', 'ultraaddons'),
                    'date'       => __('Date', 'ultraaddons'),
                    'rand'       => __('Rand', 'ultraaddons'),
                    'ID'         => __('ID', 'ultraaddons'),
                    'title'      => __('Title', 'ultraaddons'),
                    'author'     => __('Author', 'ultraaddons'),
                    'name'       => __('Name', 'ultraaddons'),
                    'parent'     => __('Parent', 'ultraaddons'),
                    'menu_order' => __('Menu Order', 'ultraaddons'),
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            '_ua_order',
            [
                'label'   => __('Order', 'ultraaddons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ase',
                'options' => [
                    'ase'  => __('Ascending Order', 'ultraaddons'),
                    'desc' => __('Descending Order', 'ultraaddons'),
                ],
            ]
        );
        $this->add_control(
            '_ua_ignore_sticky_posts', 
            [
                'label' => __( 'Ignore Sticky Posts', 'ultraaddons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    '_ua_post_type!' => ['page', 'by_id', 'category'],
                ],
                'description' => __( 'Sticky-posts ordering is visible on frontend only', 'ultraaddons' ),
            ]
        );

        $this->end_controls_section();

    }
    
    protected function ua_style_tab_general_section(){

        $this->start_controls_section(
            '_ua_blog_style_general',
            [
                'label' => esc_html__('General', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_ua_blog_style_general_background',
            [
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_post_box_content' => 'background-color: {{VALUE}};',
                ],
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_3'],
                    '_ua_show_excerpt' => ['yes'],
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_blog_box_margin',
            [
                'label' => esc_html__('Margin', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function ua_style_tab_thumb_section(){

        $this->start_controls_section(
            '_ua_section_style_image',
            [
                'label'     => __('Thumbnail', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    '_ua_show_thumb' => ['yes'],
                ],
            ]
        );
        
        $this->add_responsive_control(
            '_free_ua_blog_image_width',
            [
                'label' => __( 'Image Width', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%'   => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_free_ua_blog_image_height',
            [
                'label'      => __('Height', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%'   => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_blog_object-fit',
            [
                'label' => __( 'Object Fit', 'ultraaddons' ),
                'type' => Controls_Manager::SELECT,

                'condition' => [
                    '_free_ua_blog_image_height[size]!' => '',
                ],
                'options' => [
                    '' => __( 'Default', 'ultraaddons' ),
                    'fill' => __( 'Fill', 'ultraaddons' ),
                    'cover' => __( 'Cover', 'ultraaddons' ),
                    'contain' => __( 'Contain', 'ultraaddons' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__thumbnail img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_blog_image_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'image_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_blog_image_ofset' => ['yes'],
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
            'image_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__thumbnail img' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ua-post__area .ua-post__thumbnail img'  => '-ms-transform: translate({{image_offset_x.SIZE || 0}}{{UNIT}}, {{image_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{image_offset_x.SIZE || 0}}{{UNIT}}, {{image_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{image_offset_x.SIZE || 0}}{{UNIT}}, {{image_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ua-post__area .ua-post__thumbnail img'   => '-ms-transform: translate({{image_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{image_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{image_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{image_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{image_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{image_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ua-post__area .ua-post__thumbnail img'   => '-ms-transform: translate({{image_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{image_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{image_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{image_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{image_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{image_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
            ]
        );

        $this->end_popover();
    
        $this->add_responsive_control(
            '_ua_blog_image_pading',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__thumbnail img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_blog_image_radius',
            [
                'label'      => __('Border Radius', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['%', 'px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->end_controls_section();

    }

    protected function ua_style_tab_title_section(){
        $this->start_controls_section(
            '_ua_blog_section_style_title',
            [
                'label'     => __('Title', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_show_title' => ['yes'],
                ]
            ]
        );
        $this->start_controls_tabs( '_ua_blog_title_tabs' );

        $this->start_controls_tab( '_ua_blog_title_style',
            [ 
                'label' => esc_html__( 'Style', 'ultraaddons')
            ] 
        );

        $this->add_control(
        '_ua_blog_title_color',
            [
                'label'     => __('Text Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__title a' => 'color: {{VALUE}};',
                ],
            ]
        );
         $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_blog_title_typography',
                'selector' => '{{WRAPPER}} .ua-post__area .ua-post__title a, {{WRAPPER}} .ua-post__area .ua-post__title',
            ]
        );
        $this->add_control(
            '_ua_blog_title_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'blog_title_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_blog_title_ofset' => ['yes'],
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
            '_ua_blog_title_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__title' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ua-post__area .ua-post__title'  => '-ms-transform: translate({{blog_title_offset_x.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_title_offset_x.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{blog_title_offset_x.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ua-post__area .ua-post__title'   => '-ms-transform: translate({{blog_title_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_title_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{blog_title_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ua-post__area .ua-post__title'   => '-ms-transform: translate({{blog_title_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_title_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{blog_title_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_title_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
            ]
        );

        $this->end_popover();
        
        $this->add_responsive_control(
            '_ua_blog_title_pading',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( '_ua_blog_title_hover',
            [ 
                'label' => esc_html__( 'Hover', 'ultraaddons')
            ] 
        );
        
        $this->add_control(
        '_ua_blog_title_h_color',
            [
                'label'     => __('Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__title a:hover' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function ua_style_tab_content_section(){
        $this->start_controls_section(
            '_ua_blog_section_style_content',
            [
                'label'     => __('Content', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_3', '_skin_4'],
                    '_ua_show_excerpt' => ['yes'],
                ],
            ]
        );

         $this->add_control(
        '_ua_blog_content_color',
            [
                'label'     => __('Text Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-post__area .ua-post__content p' => 'color: {{VALUE}};',
                ],
            ]
        );
         $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_blog_content_typography',
                'selector' => '{{WRAPPER}} .ua-post__area .ua-post__content p, {{WRAPPER}} .ua-post__area .ua-post__content',
            ]
        );
        
        $this->add_responsive_control(
            '_ua_blog_content_pading',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function ua_style_tab_category_section(){
        $this->start_controls_section(
            '_ua_blog_section_style_cat',
            [
                'label'     => __('Category', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_2', '_skin_3'],
                    '_ua_show_category' => ['yes'],
                ],
            ]
        );
        
        $this->add_control(
        '_ua_blog_cat_color',
            [
                'label'     => __('Text Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__category' => 'color: {{VALUE}};',
                ],
                'global'    => [
                    'default' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_blog_cat_typography',
                'selector' => '{{WRAPPER}} .ua-post__area .ua-post__category',
            ]
        );

        $this->add_control(
            '_ua_blog_cat_bg_color_after',
                [
                    'label'     => __('Background After Color', 'ultraaddons'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ua_blog_grid_masonory_post.style_8 .ua_tag:after' => 'background-color: {{VALUE}};',
                    ],
                    'condition'   => [
                        '_ua_blog_skin' => ['_skin_3'],
                        '_ua_show_category' => ['yes'],
                    ],
                ]
            );
        $this->add_control(
        '_ua_blog_cat_bg_color',
            [
                'label'     => __('Background Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__category' => 'background-color: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_control(
            '_ua_blog_cat_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            '_ua_blog_cat_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_blog_cat_ofset' => ['yes'],
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
            '_ua_blog_cat_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__category' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ua-post__area .ua-post__category'  => '-ms-transform: translate({{blog_cat_offset_x.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_cat_offset_x.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{blog_cat_offset_x.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ua-post__area .ua-post__category'   => '-ms-transform: translate({{blog_cat_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_cat_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{blog_cat_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ua-post__area .ua-post__category'   => '-ms-transform: translate({{blog_cat_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_cat_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{blog_cat_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_cat_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
            ]
        );

        $this->end_popover();
        
        $this->add_responsive_control(
            '_ua_blog_cat_pading',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_blog_cat_radius',
            [
                'label'      => __('Border Radius', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['%', 'px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->end_controls_section();
    }

    protected function ua_style_tab_author_section(){
        $this->start_controls_section(
            '_ua_blog_section_style_auth',
            [
                'label'     => __('Author', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    '_ua_blog_skin' => ['_skin_2', '_skin_3', '_skin_4'],
                    '_ua_show_author' => ['yes'],
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_blog_author_tabs' );


        $this->start_controls_tab( '_ua_blog_author_style',
            [ 
                'label' => esc_html__( 'Normal', 'ultraaddons')
            ] 
        );

        $this->add_control(
        '_ua_blog_auth_color',
            [
                'label'     => __('Text Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post_author a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_blog_auth_typography',
                'selector' => '{{WRAPPER}} .ua-post__area .ua-post_author a, .ua-post__area .ua-post_author',
            ]
        );
        $this->add_control(
            '_ua_blog_auth_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            '_ua_blog_auth_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_blog_auth_ofset' => ['yes'],
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
            '_ua_blog_auth_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post_author' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ua-post__area .ua-post_author'  => '-ms-transform: translate({{blog_auth_offset_x.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_auth_offset_x.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{blog_auth_offset_x.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ua-post__area .ua-post_author'   => '-ms-transform: translate({{blog_auth_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_auth_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{blog_auth_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ua-post__area .ua-post_author'   => '-ms-transform: translate({{blog_auth_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_auth_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{blog_auth_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_auth_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
            ]
        );

        $this->end_popover();
        
        $this->add_responsive_control(
            '_ua_blog_auth_pading',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post_author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_blog_auth_radius',
            [
                'label'      => __('Border Radius', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['%', 'px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( '_ua_blog_author_hover',
            [ 
                'label' => esc_html__( 'Hover', 'ultraaddons')
            ] 
        );
        
        $this->add_control(
        '_ua_blog_auth_h_color',
            [
                'label'     => __('Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post_author a:hover' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->end_controls_tab();
                
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

    protected function ua_style_tab_date_section(){
        $this->start_controls_section(
            '_ua_blog_section_style_date',
            [
                'label'     => __('Date', 'ultraaddons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_show_date' => ['yes'],
                ]
            ]
        );
        $this->start_controls_tabs( '_ua_blog_date_tabs' );


        $this->start_controls_tab( '_ua_blog_date_style',
            [ 
                'label' => esc_html__( 'Normal', 'ultraaddons')
            ] 
        );
        $this->add_control(
        '_ua_blog_date_color',
            [
                'label'     => __('Text Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__date a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => '_ua_blog_date_typography',
                'selector' => '{{WRAPPER}} .ua-post__area .ua-post__date a, .ua-post__area .ua-post__date',
            ]
        );
        $this->add_control(
            '_ua_blog_date_ofset',
            [
                'label'        => __('Offset', 'ultraaddons'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_on'     => __('Custom', 'ultraaddons'),
                'label_off'    => __('None', 'ultraaddons'),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            '_ua_blog_date_offset_x',
            [
                'label'       => __('Offset Left', 'ultraaddons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'em', 'rem'],
                'condition'   => [
                    '_ua_blog_date_ofset' => ['yes'],
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
            '_ua_blog_date_offset_y',
            [
                'label'      => __('Offset Top', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__date' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '(desktop){{WRAPPER}} .ua-post__area .ua-post__date'  => '-ms-transform: translate({{blog_date_offset_x.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_date_offset_x.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{blog_date_offset_x.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ua-post__area .ua-post__date'   => '-ms-transform: translate({{blog_date_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_date_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{blog_date_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ua-post__area .ua-post__date'   => '-ms-transform: translate({{blog_date_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{blog_date_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{blog_date_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{blog_date_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
            ]
        );

        $this->end_popover();
        
        $this->add_responsive_control(
            '_ua_blog_date_pading',
            [
                'label'      => __('Padding', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            '_ua_blog_date_radius',
            [
                'label'      => __('Border Radius', 'ultraaddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['%', 'px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ua-post__area .ua-post__date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab( '_ua_blog_date_hover',
            [ 
                'label' => esc_html__( 'Hover', 'ultraaddons')
            ] 
        );
        
        $this->add_control(
        '_ua_blog_date_h_color',
            [
                'label'     => __('Color', 'ultraaddons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-post__area .ua-post__date a:hover' => 'color: {{VALUE}};',
                ],
                
            ]
        );

        $this->end_controls_tab();
                
        $this->end_controls_tabs();
         
        $this->end_controls_section();
    }

    protected function render_title(){
        $settings = $this->get_settings_for_display();
        if (! $settings['_ua_show_title'] ) {
            return;
        }
        $optional_attributes_html = $this->get_optional_link_attributes_html();

        $tag = !empty( $settings['_ua_title_tag'] ) ? $settings['_ua_title_tag'] : 'h3';
        ?>
    
    <<?php echo $tag; ?> class="ua_title ua-post__title"> <a href="<?php echo $this->current_permalink; ?>" <?php echo $optional_attributes_html; ?>><?php the_title();?></a> </<?php echo $tag; ?>>
    <?php
    }
    protected function render_thumbnail($_image_width, $_image_height){
        $settings = $this->get_settings_for_display();
        if (!$settings[ '_ua_show_thumb' ]) {
            return;
        }
       
         if ( has_post_thumbnail() ) : 
            ultraaddons_get_the_post_thumbnail( array(
            'size'   => 'custom',
            'width'  => $_image_width,
            'height' => $_image_height,
            ) ); 
         else: 
            ultraaddons_image_placeholder( 480, 480 );
         endif;
    }

    protected function render_thumbnails(){
        $settings = $this->get_settings_for_display();
        if (!$settings[ '_ua_show_thumb' ]) {
            return;
        }
        
        ?>
        <div class="ua-post__thumbnail">
            <?php
                 if ( has_post_thumbnail() ) :
                 $class = '';
                 switch ( $settings['_ua_blog_skin'] ) {
                      case '_skin_1':
                           $class = ' ua_img_res zoom_in_img ';
                          break;
                      case '_skin_4':
                           $class = ' blog_masonry_thumb ';
                          break;
                  } 
                    $size = $settings['_ua_thumbnail_size_size'];
                    the_post_thumbnail( $size, array( 'class' => $class . 'zoom_in_img ua_thumbnail_fluid ' ) );
                 else:
                    ultraaddons_image_placeholder( 480, 480 );
                 endif;
             ?>
        </div>
    <?php
    }
    protected function render_excerpt() {
        $settings = $this->get_settings_for_display();
        if ( !$settings['_ua_show_excerpt'] ) {
            return;
        }

        ?>
        <div class="ua-post__excerpt ua_description ua-post__content ua_desc">
            <?php 
            if ( ! has_excerpt() ) {
                echo '<p>';
                echo wp_trim_words( get_the_content(), 10, '...' );
               echo '</p>';
            } else { 
                the_excerpt();
            }?>
        </div>
        <?php
    }

    protected function render_category( $taxonomy = 'category', $type = 'single' ){ //single or multiple
        $settings = $this->get_settings_for_display();
        if (!$settings['_ua_show_category']) {
            return;
        }
        $output = '';
        $class = $settings['_ua_blog_skin'] == '_skin_2' || $settings['_ua_blog_skin'] == '_skin_3' ? 'ua_tag ua-post__category' : 'd-inline-block ua_tag sa ua-post__category';
        if( 'category' == $taxonomy ) {
            if( $type == 'single' ){
                $category = get_the_category();
                if( !empty( $category ) ) {
                    $output = '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) .'" class="'.$class.'">'. esc_html( $category[0]->cat_name ) .'</a>';
                }
            }
            else{
                $category = (object) get_the_category_list(', ');
                if( !empty( $category ) ) {
                    $output = '<a href="#" class="'.$class.'">'. esc_html( $category[0]->cat_name ) .'</a>';
                }
                
            }
        }
        else {
            $terms = get_the_terms( get_the_ID(), $taxonomy );
            $term_link = get_term_link( $terms[0], $taxonomy );

            if( !empty( $terms ) ) {
                $output = '<a href="' . esc_url( $term_link ) .'" class="'.$class.'">'. esc_html( $terms[0]->name ) .'</a>';
            }
        }
        echo $output;
    }
    protected function render_author(){
        $settings = $this->get_settings_for_display();
        if (!$settings['_ua_show_author']) {
            return;
        }

        ?>
        <p class="ua_post_author ua-post_author">
         <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author();?></a>
     </p>
     <?php
    }
    protected function render_avatar() {
        $args = array(
        'size'          => 45,
        'height'        => 45,
        'width'         => 45,
        'class'         => 'ua_author_img',
    );
        ?>
        <div class="ua-post__avatar">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 45, '', get_the_author_meta( 'display_name' ), $args ); ?>
        </div>
        <?php
    }
    protected function render_date() {   
    $settings = $this->get_settings_for_display();
    if (!$settings['_ua_show_date']) {
            return;
        }
        $author_prefix_text = $settings['_ua_blog_skin'] == '_skin_1' ? ' By' : '';
        echo '<p class="ua_date ua-post__date"><a href="#">'.apply_filters('the_date', get_the_date(), get_option('date_format'), '', ''). $author_prefix_text . '</a></p>';
    }
    protected function render_tag() {    
        $settings = $this->get_settings_for_display();
        if (!$settings['_ua_show_tag']) {
            return;
        }
        $output = '';
        $post_tags = get_the_tags();
        $separator = ', ';
        if (!empty($post_tags)) {
            $output .= '<ul class="tag_list">';
            foreach ($post_tags as $tag) {
                $output .= '<li><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a></li>' . $separator;
            }
             $output .= '</ul>';
            echo trim($output, $separator);

        }
    }
    protected function render_meta()
    {
        $settings = $this->get_settings_for_display();
        if (!$settings['_ua_show_author'] && !$settings['_ua_show_date']) {
            return;
        }
        ?>
        <div class="ua_post_meta">
            <?php 
                $this->render_date();
                $this->render_author();
            ?> 
        </div>
    <?php
}
protected function render_read_more() {
     $settings = $this->get_settings_for_display();
    if ( ! $settings['_ua_show_read_more'] ) {
        return;
    }

    $optional_attributes_html = $this->get_optional_link_attributes_html();

    ?>
        <a class="ua-post__read-more read_more_btn" href="<?php echo $this->current_permalink; ?>" <?php echo $optional_attributes_html; ?>>
            <?php echo $settings['_ua_read_more_text']; ?>
        </a>
    <?php
}
protected function get_optional_link_attributes_html() {
    $settings = $this->get_settings_for_display();
    $new_tab_setting_key = $settings['_ua_open_new_tab'];
    $optional_attributes_html = 'yes' === $new_tab_setting_key ? 'target="_blank"' : '';

    return $optional_attributes_html;
}
protected function get_grid_options( array $settings ) {
    $grid_options = [
        'type'  => $settings['_ua_masonary_type'],
        'ratio' => $settings['_ua_metro_image_ratio']['size'],
    ];

    // Columns.
    if ( ! empty( $settings['_ua_grid_columns'] ) ) {
        $grid_options['columns'] = $settings['_ua_grid_columns'];
    }

    if ( ! empty( $settings['_ua_grid_columns_tablet'] ) ) {
        $grid_options['columnsTablet'] = $settings['_ua_grid_columns_tablet'];
    }

    if ( ! empty( $settings['_ua_grid_columns_mobile'] ) ) {
        $grid_options['columnsMobile'] = $settings['_ua_grid_columns_mobile'];
    }

    // Gutter
    if ( ! empty( $settings['_ua_grid_gutter'] ) ) {
        $grid_options['gutter'] = $settings['_ua_grid_gutter'];
    }

    if ( ! empty( $settings['_ua_grid_gutter_tablet'] ) ) {
        $grid_options['gutterTablet'] = $settings['_ua_grid_gutter_tablet'];
    }

    if ( ! empty( $settings['_ua_grid_gutter_mobile'] ) ) {
        $grid_options['gutterMobile'] = $settings['_ua_grid_gutter_mobile'];
    }

    // Zigzag height.
    if ( ! empty( $settings['_ua_zigzag_height'] ) ) {
        $grid_options['zigzagHeight'] = $settings['_ua_zigzag_height'];
    }

    if ( ! empty( $settings['_ua_zigzag_height_tablet'] ) ) {
        $grid_options['zigzagHeightTablet'] = $settings['_ua_zigzag_height_tablet'];
    }

    if ( ! empty( $settings['_ua_zigzag_height_mobile'] ) ) {
        $grid_options['zigzagHeightMobile'] = $settings['_ua_zigzag_height_mobile'];
    }

    if ( ! empty( $settings['_ua_zigzag_reversed'] ) && 'yes' === $settings['_ua_zigzag_reversed'] ) {
        $grid_options['zigzagReversed'] = 1;
    }

    return $grid_options;
}

protected function get_grid_layout_four_options( array $settings ) {
    $grid_options = [
        'type'  => $settings['_ua_masonary_type_four'],
        'ratio' => 'null',
    ];

    // Columns.
    if ( ! empty( $settings['_ua_grid_columns_four'] ) ) {
        $grid_options['columns'] = $settings['_ua_grid_columns_four'];
    }

    if ( ! empty( $settings['_ua_grid_columns_four_tablet'] ) ) {
        $grid_options['columnsTablet'] = $settings['_ua_grid_columns_four_tablet'];
    }

    if ( ! empty( $settings['_ua_grid_columns_four_mobile'] ) ) {
        $grid_options['columnsMobile'] = $settings['_ua_grid_columns_four_mobile'];
    }

    // Gutter
    if ( ! empty( $settings['_ua_grid_gutter_four'] ) ) {
        $grid_options['gutter'] = $settings['_ua_grid_gutter_four'];
    }

    if ( ! empty( $settings['_ua_grid_gutter_four_tablet'] ) ) {
        $grid_options['gutterTablet'] = $settings['_ua_grid_gutter_four_tablet'];
    }

    if ( ! empty( $settings['_ua_grid_gutter_four_mobile'] ) ) {
        $grid_options['gutterMobile'] = $settings['_ua_grid_gutter_four_mobile'];
    }

    // Zigzag height.
    if ( ! empty( $settings['_ua_zigzag_height_four'] ) ) {
        $grid_options['zigzagHeight'] = $settings['_ua_zigzag_height_four'];
    }

    if ( ! empty( $settings['_ua_zigzag_height_four_tablet'] ) ) {
        $grid_options['zigzagHeightTablet'] = $settings['_ua_zigzag_height_four_tablet'];
    }

    if ( ! empty( $settings['_ua_zigzag_height_four_mobile'] ) ) {
        $grid_options['zigzagHeightMobile'] = $settings['_ua_zigzag_height_four_mobile'];
    }

    if ( ! empty( $settings['_ua_zigzag_reversed_four'] ) && 'yes' === $settings['_ua_zigzag_reversed_four'] ) {
        $grid_options['zigzagReversed'] = 1;
    }

    return $grid_options;
}

    protected function query_posts() {
        $settings = $this->get_settings_for_display();
        //order
        $order_by = !empty($settings['_ua_order_by']) ? $settings['_ua_order_by'] : 'date';
        $order    = !empty($settings['_ua_order']) ? $settings['_ua_order'] : 'asc';
        //post type
        $post_type = !empty($settings['_ua_post_type']) ? $settings['_ua_post_type'] : 'post';
        //post sticky
        $sticky_post = $settings['_ua_ignore_sticky_posts'] ? true : false;
        //posts per page
        $posts_per_page = $settings['_ua_posts_per_page'];

        $arrayType = ['page', 'by_id', 'category'];

        $query_args = [
            'post_type'      =>  $post_type,
            'posts_ids'      => [],
            'orderby'        => $order_by,
            'order'          => $order,
            'offset'         => 0,
            'posts_per_page' => 6,
           
        ];
        if( !empty( $post_type ) && !in_array($post_type, $arrayType) ){
            $sticky_args = array(
                '_ua_ignore_sticky_posts' => $sticky_post,
            );

            $query_args = array_merge( $query_args, $sticky_args );
        }
        if( !empty( $posts_per_page )){
            $per_page_args = array(
                '_ua_posts_per_page' => $posts_per_page,
            );

            $query_args = array_merge( $query_args, $per_page_args );
        }
        $tax_query[] = [
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => ['post-format-quote', 'post-format-link'],
            'operator' => 'NOT IN',
        ];
        if ( !empty( $tax_query ) ) {
            $tax_query = array_merge( ['relation' => 'AND'], $tax_query );
            $query_args = array_merge( $query_args, ['tax_query' => $tax_query] );
        }
        
        $ua_query = new \WP_Query($query_args);
        return $ua_query;
    }
    //Html render
    protected function render(){ 
        $settings = $this->get_settings_for_display();
        
        $_ua_blog_skin  = isset($settings['_ua_blog_skin']) && !empty($settings['_ua_blog_skin']) ? $settings['_ua_blog_skin'] : '_skin_1';
   
        switch ($_ua_blog_skin) {
            case '_skin_1':
                 $this->_ua_blog_skin_one();
                break;
            case '_skin_2':
                 $this->_ua_blog_skin_two();
                break;
            case '_skin_3':
                 $this->_ua_blog_skin_three();
                break;
            case '_skin_4':
                 $this->_ua_blog_skin_four();
                break;
            default:
                $this->_ua_blog_skin_one();
                break;
        }
        ?>
        <script>
            jQuery(".ua_addons_grid_wrapper").each(function () {
                var ua_addons_grid_wrapper = jQuery('.ua_addons_grid_wrapper');
                if (ua_addons_grid_wrapper.length) {
                    jQuery(this).uaAddonsGridLayout();
                }
            });
        </script>   
        <?php
    }

    protected function _ua_blog_skin_one(){ 
        $settings = $this->get_settings_for_display();
        
        $query_posts = $this->query_posts();
      
        if (!$query_posts->found_posts) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', [
            'ua_addons_grid_wrapper ua_grid_metro',
            'style-masonary',
        ] );

        $this->add_render_attribute( 'wrapper', 'class', 'blog-grid-masonary' );
 
        $grid_options = $this->get_grid_options( $settings );

        $this->add_render_attribute( 'wrapper', 'data-grid', wp_json_encode( $grid_options ) );
        if ( isset( $settings['_ua_grid_metro_layout'] ) && !empty($settings['_ua_grid_metro_layout']) ) {
            $metro_layout = [];

        foreach ( $settings['_ua_grid_metro_layout'] as $key => $value ) {
            $metro_layout[] = $value['size'];
            
            }
        } else {
            $metro_layout = [
                '2:2',
                '1:1',
                '1:1',
                '2:2',
                '1:1',      
                '1:1',      
            ];
        }
        if ( count( $metro_layout ) < 1 ) {
            return;
        }
        $metro_layout_count = count( $metro_layout );
        $metro_item_count   = 0;
        $count              = $query_posts->post_count;
        ?>

            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua_addons_grid">
                <div class="grid-sizer"></div>
                    <?php 
                    while ( $query_posts->have_posts() ) :
                        $query_posts->the_post();

                        $this->current_permalink = get_permalink();
                        $classes = "grid-item";
                        $size   = $metro_layout[ $metro_item_count ];

                        $ratio  = explode( ':', $size );
                  
                        $ratioW = $ratio[0];
                        $ratioH = $ratio[1];

                        $_image_width  = $settings['_ua_metro_image_size_width'];
                        $_image_height = $_image_width * isset($settings['_ua_metro_image_ratio']['size'])? $settings['_ua_metro_image_ratio']['size'] : '';
                         
                        if ( in_array( $ratioW, array( '2' ) ) ) {
                            $_image_width *= 1;
                        }

                        if ( in_array( $ratioH, array( '1.3', '2' ) ) ) {
                            $_image_height *= 2;
                        }
                        ?>
                        <div class="<?php echo $classes; ?>" data-width="<?php echo esc_attr( $ratioW ); ?>" data-height="<?php echo esc_attr( $ratioH ); ?>">

                            <div class="grid-item-height" style="height: 950px;">
                                <div class="grid-item-content ua_masonry_blog_post zoom_in_effect ua-post__area blog_grid_masonory">
                                    <a href="<?php echo $this->current_permalink; ?>" class="ua_masonry_blog_thumb">
                                        <?php $this->render_thumbnails(); ?>
                                    </a>
                                    <?php $this->render_category();  ?>
                                    <div class="ua_masonry_blog_content blog_grid_masonory_content">
                                        <div class="ua_post_meta">
                                            <?php
                                                $this->render_date(); 
                                                $this->render_author();
                                            ?>
                                        </div>
                                        <?php $this->render_title(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $metro_item_count++;
                        if ( $metro_item_count == $count || $metro_layout_count == $metro_item_count ) {
                            $metro_item_count = null;
                        }
                    endwhile; 
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        <?php 
    }

    protected function render_post_header() {
        $settings = $this->get_settings_for_display();
        ?>
        <div <?php post_class( [  $settings['_ua_blog_skin'], 'ua_row' ] ); ?>>
        <?php
    }

    protected function render_post_footer() {
        ?>
        </div>
        <?php
    }
    protected function _ua_blog_skin_two()
    { 
        $settings = $this->get_settings_for_display();
        
        $query_posts = $this->query_posts();
      
        if (!$query_posts->found_posts) {
            return;
        }
        //Columns
        $columns = isset($settings['_ua_blog_columns']) && !empty($settings['_ua_blog_columns']) ? $settings['_ua_blog_columns'] : 4;
        
        $this->render_post_header();
        while ( $query_posts->have_posts() ) :
              $query_posts->the_post();
              $this->current_permalink = get_permalink();

        ?>
         <div class="ua_col_lg_<?php echo $columns; ?> ua_col_sm_<?php echo $settings['_ua_blog_columns_tablet']; ?>">
            
            <div class="ua-post__area blog_grid_masonory style_5 zoom_in_effect">
                <?php 
                if ( $settings[ '_ua_show_thumb' ] == 'yes'):
                 ?>
                <a href="<?php echo $this->current_permalink; ?>" class="post_thumb">
                    <?php $this->render_thumbnails();?>
                </a>
            <?php endif; ?>
                 <?php $this->render_category();  ?>
                <div class="blog_grid_masonory_content">
                     <?php $this->render_title(); ?>
                    <div class="ua_post_meta">
                        <?php
                            $this->render_author();
                            $this->render_date(); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
         <?php endwhile;
         wp_reset_postdata(); 
           $this->render_post_footer();
          ?>
   <?php }
    protected function _ua_blog_skin_three()
    { 
        $settings = $this->get_settings_for_display();
        
        $query_posts = $this->query_posts();
      
        if (!$query_posts->found_posts) {
            return;
        }
        //Columns
        $columns = isset($settings['_ua_blog_columns']) && !empty($settings['_ua_blog_columns']) ? $settings['_ua_blog_columns'] : 4;
        
        $this->render_post_header();
        while ( $query_posts->have_posts() ) :
              $query_posts->the_post();
              $this->current_permalink = get_permalink();
        ?>
         <div class="ua_col_lg_<?php echo $columns; ?> ua_col_sm_<?php echo $settings['_ua_blog_columns_tablet']; ?>">
            
            <div class="ua-post__area ua_blog_grid_masonory_post style_8 zoom_in_effect">
                 <a href="<?php echo $this->current_permalink; ?>" class="ua_blog_grid_masonory_img">
                    <?php $this->render_thumbnails();?>
                </a>
                <div class="ua_post_box_content">
                    <?php 
                        $this->render_category();
                        $this->render_title();
                        $this->render_excerpt()
                     ?>
                    <div class="ua_post_meta">
                        <?php
                            $this->render_avatar();
                            $this->render_author();
                            $this->render_date(); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
         <?php endwhile;
         wp_reset_postdata(); 
           $this->render_post_footer();
          ?>
   <?php }

   protected function _ua_blog_skin_four(){ 
        $settings = $this->get_settings_for_display();
        
        $query_posts = $this->query_posts();
      
        if (!$query_posts->found_posts) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', [
            'ua_addons_grid_wrapper ua_grid_metro',
            'style-masonary',
        ] );

        $this->add_render_attribute( 'wrapper', 'class', 'blog-grid-masonary' );

        $grid_options = $this->get_grid_layout_four_options( $settings );

        $this->add_render_attribute( 'wrapper', 'data-grid', wp_json_encode( $grid_options ) );

        $metro_item_count   = 0;
    
        ?>
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua_addons_grid loaded">
                <div class="grid-sizer"></div>
                    <?php 
                    while ( $query_posts->have_posts() ) :
                        $query_posts->the_post();
                        $this->current_permalink = get_permalink();
                        $classes = "grid-item";

                        $_image_width  = $settings['_ua_metro_image_size_width_four'];
                        $_image_height = $_image_width * isset($settings['_ua_metro_image_ratio_four']['size'])? $settings['_ua_metro_image_ratio_four']['size'] : '';

                    ?>
                      <div class="<?php echo $classes; ?>">
                        
                        <div class="ua-post__area ua_blog_grid_masonory_post style_6">
                            <a href="<?php echo $this->current_permalink; ?>" class="ua_blog_grid_masonory_post_thumb">
                               <?php $this->render_thumbnails(); ?>
                            </a>
                            <div class="ua_blog_grid_masonory_post_inner">
                                <div class="ua_post_meta">
                                    <?php 
                                        $this->render_avatar(); 
                                        $this->render_author(); 
                                        $this->render_date(); 
                                        ?>
                                </div>
                                <div class="ua_blog_grid_masonory_content">
                                    <?php $this->render_title(); ?>
                                    <?php $this->render_excerpt(); ?>
                                    <?php $this->render_read_more(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
                 </div>
            </div>
    <?php }

}


