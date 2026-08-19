<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Flip Box Widget
 *
 * An interactive, 3D flip card widget with smooth animations (Flip, Zoom, Fade),
 * 3D depth perspective effects, customizable front and back sides, image/icon media,
 * Elementor saved template support, and versatile linking/call-to-action buttons.
 *
 * @since 1.2.0
 * @package UltraAddons
 */
class Flip_Box extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-flip-box',
            ULTRA_ADDONS_ASSETS . 'css/widgets/flip-box.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-flip-box' );
    }

    /**
     * Style dependency
     */
    public function get_style_depends() {
        return [ 'ultraaddons-flip-box' ];
    }

    /**
     * Widget keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'flip', 'flipbox', 'flip box', 'card', 'flip card', 'rotate', '3d', 'interactive', 'banner' ];
    }

    /**
     * Get Saved Elementor Templates list
     *
     * @return array
     */
    protected function get_elementor_templates() {
        $templates = get_posts([
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        $options = [ '' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ) ];

        if ( ! empty( $templates ) && ! is_wp_error( $templates ) ) {
            foreach ( $templates as $template ) {
                $options[ $template->ID ] = $template->post_title;
            }
        }

        return $options;
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_settings_controls();
        $this->content_card_controls();
        $this->content_link_controls();

        // Style Tab
        $this->style_box_controls();
        $this->style_image_controls();
        $this->style_icon_controls();
        $this->style_typography_controls();
        $this->style_button_controls();
    }

    /*==========================================================================
     * CONTENT TAB — Flipbox Settings (Trigger, Type, 3D, Speed, Height)
     *========================================================================*/
    protected function content_settings_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_settings',
            [
                'label' => esc_html__( 'Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_flipbox_event_type',
            [
                'label'   => esc_html__( 'Choose Event', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'hover' => [
                        'title' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-button',
                    ],
                    'click' => [
                        'title' => esc_html__( 'Click', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-click',
                    ],
                ],
                'default' => 'hover',
                'toggle'  => true,
            ]
        );

        $this->add_control(
            'ua_flipbox_type',
            [
                'label'       => esc_html__( 'Flipbox Type', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'animate-left',
                'label_block' => false,
                'options'     => [
                    'animate-left'     => esc_html__( 'Flip Left', 'ultraaddons-elementor-lite' ),
                    'animate-right'    => esc_html__( 'Flip Right', 'ultraaddons-elementor-lite' ),
                    'animate-up'       => esc_html__( 'Flip Top', 'ultraaddons-elementor-lite' ),
                    'animate-down'     => esc_html__( 'Flip Bottom', 'ultraaddons-elementor-lite' ),
                    'animate-zoom-in'  => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                    'animate-zoom-out' => esc_html__( 'Zoom Out', 'ultraaddons-elementor-lite' ),
                    'animate-fade-in'  => esc_html__( 'Fade In', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_3d',
            [
                'label'        => esc_html__( '3D Depth', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Off', 'ultraaddons-elementor-lite' ),
                'return_value' => 'ua-flip-box--3d',
                'default'      => '',
                'condition'    => [
                    'ua_flipbox_type' => [
                        'animate-left',
                        'animate-right',
                        'animate-up',
                        'animate-down',
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_flip_speed',
            [
                'label'      => esc_html__( 'Flip Speed', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'ms', 's' ],
                'range'      => [
                    'ms' => [
                        'min'  => 100,
                        'step' => 10,
                        'max'  => 3000,
                    ],
                    's'  => [
                        'min'  => 0.1,
                        'step' => 0.1,
                        'max'  => 5,
                    ],
                ],
                'default'    => [
                    'unit' => 'ms',
                    'size' => 500,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-card' => 'transition-duration: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-flip-box-front, {{WRAPPER}} .ua-flip-box-back' => 'transition-duration: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_height_mode',
            [
                'label'       => esc_html__( 'Height Mode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'fixed',
                'options'     => [
                    'fixed' => [
                        'title' => esc_html__( 'Fixed Height', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-field',
                    ],
                    'auto'  => [
                        'title' => esc_html__( 'Auto Height', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-lightbox-expand',
                    ],
                ],
                'description' => esc_html__( 'Choose between fixed height or auto height that adapts to content.', 'ultraaddons-elementor-lite' ),
                'toggle'      => false,
            ]
        );

        $this->add_control(
            'ua_flipbox_height_adjustment',
            [
                'label'     => esc_html__( 'Adjustment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'maximum',
                'options'   => [
                    'maximum' => [
                        'title' => esc_html__( 'Maximum Content Height', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-align-stretch-v',
                    ],
                    'dynamic' => [
                        'title' => esc_html__( 'Based on Visible Content', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-align-end-v',
                    ],
                ],
                'condition' => [
                    'ua_flipbox_height_mode' => 'auto',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_height',
            [
                'label'          => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => [ 'px', 'em', 'vh', '%' ],
                'range'          => [
                    'px' => [
                        'min'  => 100,
                        'step' => 1,
                        'max'  => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default'        => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 280,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 260,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .ua-flip-box-fixed-height' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-flip-box-fixed-height .ua-flip-box-card' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-flip-box-fixed-height .ua-flip-box-front, {{WRAPPER}} .ua-flip-box-fixed-height .ua-flip-box-back' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition'      => [
                    'ua_flipbox_height_mode' => 'fixed',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Front & Back Content (Tabs)
     *========================================================================*/
    protected function content_card_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_content',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->start_controls_tabs( 'ua_flipbox_content_tabs' );

        // ------------------------- FRONT TAB -------------------------
        $this->start_controls_tab(
            'ua_flipbox_tab_front',
            [
                'label' => esc_html__( 'Front', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_front_content_type',
            [
                'label'   => esc_html__( 'Content Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'content'  => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Saved Templates', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'content',
            ]
        );

        $this->add_control(
            'ua_flipbox_front_template',
            [
                'label'       => esc_html__( 'Choose Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_elementor_templates(),
                'label_block' => true,
                'condition'   => [
                    'ua_flipbox_front_content_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_media_type',
            [
                'label'     => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'img'  => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                    'icon' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'icon',
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_image',
            [
                'label'     => esc_html__( 'Front Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                    'ua_flipbox_front_media_type'   => 'img',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_image_resizer',
            [
                'label'     => esc_html__( 'Image Width', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 100,
                    'unit' => 'px',
                ],
                'range'     => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-image-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                    'ua_flipbox_front_media_type'   => 'img',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'front_thumbnail',
                'default'   => 'full',
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                    'ua_flipbox_front_media_type'   => 'img',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_icon',
            [
                'label'     => esc_html__( 'Front Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-snowflake',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                    'ua_flipbox_front_media_type'   => 'icon',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_title',
            [
                'label'       => esc_html__( 'Front Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'default'     => esc_html__( 'Front Title', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_flipbox_front_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_title_tag',
            [
                'label'     => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h3',
                'options'   => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_text',
            [
                'label'       => esc_html__( 'Front Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => '<p>' . esc_html__( 'This is front side content.', 'ultraaddons-elementor-lite' ) . '</p>',
                'condition'   => [
                    'ua_flipbox_front_content_type' => 'content',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_vertical_align',
            [
                'label'                => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'                 => Controls_Manager::CHOOSE,
                'options'              => [
                    'top'    => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'              => 'middle',
                'selectors_dictionary' => [
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors'            => [
                    '{{WRAPPER}} .ua-flip-box-front' => 'align-items: {{VALUE}};',
                ],
                'condition'            => [
                    'ua_flipbox_front_content_type' => 'content',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_align',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-inner' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'ua_flipbox_front_content_type' => 'content',
                ],
            ]
        );

        $this->end_controls_tab();

        // ------------------------- BACK TAB -------------------------
        $this->start_controls_tab(
            'ua_flipbox_tab_back',
            [
                'label' => esc_html__( 'Back', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_back_content_type',
            [
                'label'   => esc_html__( 'Content Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'content'  => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                    'template' => esc_html__( 'Saved Templates', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'content',
            ]
        );

        $this->add_control(
            'ua_flipbox_back_template',
            [
                'label'       => esc_html__( 'Choose Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => $this->get_elementor_templates(),
                'label_block' => true,
                'condition'   => [
                    'ua_flipbox_back_content_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_media_type',
            [
                'label'     => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'img'  => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                    'icon' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'icon',
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_image',
            [
                'label'     => esc_html__( 'Back Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                    'ua_flipbox_back_media_type'   => 'img',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_image_resizer',
            [
                'label'     => esc_html__( 'Image Width', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 100,
                    'unit' => 'px',
                ],
                'range'     => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-image-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                    'ua_flipbox_back_media_type'   => 'img',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'back_thumbnail',
                'default'   => 'full',
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                    'ua_flipbox_back_media_type'   => 'img',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_icon',
            [
                'label'     => esc_html__( 'Back Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-snowflake',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                    'ua_flipbox_back_media_type'   => 'icon',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_title',
            [
                'label'       => esc_html__( 'Back Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'default'     => esc_html__( 'Back Title', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_title_tag',
            [
                'label'     => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h3',
                'options'   => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_text',
            [
                'label'       => esc_html__( 'Back Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => '<p>' . esc_html__( 'This is back side content.', 'ultraaddons-elementor-lite' ) . '</p>',
                'condition'   => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_vertical_align',
            [
                'label'                => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'                 => Controls_Manager::CHOOSE,
                'options'              => [
                    'top'    => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'              => 'middle',
                'selectors_dictionary' => [
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors'            => [
                    '{{WRAPPER}} .ua-flip-box-back' => 'align-items: {{VALUE}};',
                ],
                'condition'            => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_align',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-inner' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Link & Action Button
     *========================================================================*/
    protected function content_link_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_link',
            [
                'label'     => esc_html__( 'Link & Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_link_type',
            [
                'label'   => esc_html__( 'Link Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'box'    => esc_html__( 'Entire Box (Whole Card)', 'ultraaddons-elementor-lite' ),
                    'title'  => esc_html__( 'Back Title Only', 'ultraaddons-elementor-lite' ),
                    'button' => esc_html__( 'Action Button', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_link',
            [
                'label'       => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ],
                ],
                'placeholder' => 'https://example.com',
                'default'     => [
                    'url' => '#',
                ],
                'condition'   => [
                    'ua_flipbox_link_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_button_text',
            [
                'label'     => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [ 'active' => true ],
                'default'   => esc_html__( 'Get Started', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'ua_flipbox_link_type' => 'button',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_button_icon',
            [
                'label'     => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'condition' => [
                    'ua_flipbox_link_type' => 'button',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_button_icon_position',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'after',
                'options'   => [
                    'before' => esc_html__( 'Before', 'ultraaddons-elementor-lite' ),
                    'after'  => esc_html__( 'After', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_flipbox_link_type' => 'button',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Flip Box Style (Backgrounds, Padding, Border, Shadow)
     *========================================================================*/
    protected function style_box_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_style_box',
            [
                'label' => esc_html__( 'Flip Box Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_flipbox_front_bg_heading',
            [
                'label' => esc_html__( 'Front Background', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'ua_flipbox_front_bg',
                'label'          => esc_html__( 'Front Background', 'ultraaddons-elementor-lite' ),
                'types'          => [ 'classic', 'gradient' ],
                'selector'       => '{{WRAPPER}} .ua-flip-box-front',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color'      => [
                        'default' => '#8a35ff',
                    ],
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_bg_heading',
            [
                'label'     => esc_html__( 'Back Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'ua_flipbox_back_bg',
                'label'          => esc_html__( 'Back Background', 'ultraaddons-elementor-lite' ),
                'types'          => [ 'classic', 'gradient' ],
                'selector'       => '{{WRAPPER}} .ua-flip-box-back',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color'      => [
                        'default' => '#502fc6',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_padding',
            [
                'label'          => esc_html__( 'Content Padding', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => [ 'px', 'em', '%' ],
                'separator'      => 'before',
                'default'        => [
                    'top'      => '30',
                    'right'    => '30',
                    'bottom'   => '30',
                    'left'     => '30',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'tablet_default' => [
                    'top'      => '25',
                    'right'    => '20',
                    'bottom'   => '25',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'mobile_default' => [
                    'top'      => '20',
                    'right'    => '15',
                    'bottom'   => '20',
                    'left'     => '15',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .ua-flip-box-front, {{WRAPPER}} .ua-flip-box-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_flipbox_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-flip-box-front, {{WRAPPER}} .ua-flip-box-back',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '6',
                    'right'    => '6',
                    'bottom'   => '6',
                    'left'     => '6',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-front, {{WRAPPER}} .ua-flip-box-back' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_flipbox_shadow',
                'selector' => '{{WRAPPER}} .ua-flip-box-front, {{WRAPPER}} .ua-flip-box-back',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Image Style
     *========================================================================*/
    protected function style_image_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_style_image',
            [
                'label'     => esc_html__( 'Image Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_flipbox_front_media_type' => 'img',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_image_style_type',
            [
                'label'   => esc_html__( 'Image Shape', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
                    'circle'  => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'radius'  => esc_html__( 'Custom Radius', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_image_custom_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-image-wrap img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_flipbox_image_style_type' => 'radius',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_image_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-image-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Icon Style (Front & Back Tabs)
     *========================================================================*/
    protected function style_icon_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_style_icon',
            [
                'label' => esc_html__( 'Icon Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'ua_flipbox_icon_style_tabs' );

        // FRONT ICON
        $this->start_controls_tab(
            'ua_flipbox_icon_tab_front',
            [
                'label' => esc_html__( 'Front', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_front_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap .ua-flip-box-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap svg'              => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 10,
                        'step' => 1,
                        'max'  => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap .ua-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap svg'              => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_flipbox_front_icon_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_icon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_icon_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // BACK ICON
        $this->start_controls_tab(
            'ua_flipbox_icon_tab_back',
            [
                'label' => esc_html__( 'Back', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_back_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap .ua-flip-box-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap svg'              => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 10,
                        'step' => 1,
                        'max'  => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap .ua-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap svg'              => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_flipbox_back_icon_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_icon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_icon_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Color & Typography (Front & Back)
     *========================================================================*/
    protected function style_typography_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_style_typography',
            [
                'label' => esc_html__( 'Color & Typography', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'ua_flipbox_typography_tabs' );

        // FRONT TYPOGRAPHY
        $this->start_controls_tab(
            'ua_flipbox_typo_tab_front',
            [
                'label' => esc_html__( 'Front', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_front_title_heading',
            [
                'label' => esc_html__( 'Title Style', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ua_flipbox_front_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_flipbox_front_title_typography',
                'selector' => '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-title',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_front_title_spacing',
            [
                'label'      => esc_html__( 'Title Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_front_content_heading',
            [
                'label'     => esc_html__( 'Content Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_flipbox_front_content_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_flipbox_front_content_typography',
                'selector' => '{{WRAPPER}} .ua-flip-box-front .ua-flip-box-desc',
            ]
        );

        $this->end_controls_tab();

        // BACK TYPOGRAPHY
        $this->start_controls_tab(
            'ua_flipbox_typo_tab_back',
            [
                'label' => esc_html__( 'Back', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_back_title_heading',
            [
                'label' => esc_html__( 'Title Style', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ua_flipbox_back_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-linked-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_flipbox_back_title_typography',
                'selector' => '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-title, {{WRAPPER}} .ua-flip-box-back .ua-flip-box-title a, {{WRAPPER}} .ua-flip-box-back .ua-flip-box-linked-title',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_back_title_spacing',
            [
                'label'      => esc_html__( 'Title Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-title, {{WRAPPER}} .ua-flip-box-back .ua-flip-box-linked-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_back_content_heading',
            [
                'label'     => esc_html__( 'Content Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_flipbox_back_content_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_flipbox_back_content_typography',
                'selector' => '{{WRAPPER}} .ua-flip-box-back .ua-flip-box-desc',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Button Style
     *========================================================================*/
    protected function style_button_controls() {
        $this->start_controls_section(
            'ua_flipbox_section_style_button',
            [
                'label'     => esc_html__( 'Button Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_flipbox_link_type'         => 'button',
                    'ua_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_flipbox_button_tabs' );

        // NORMAL STATE
        $this->start_controls_tab(
            'ua_flipbox_button_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_button_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-flip-box-button svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7048ff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_flipbox_button_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-flip-box-button',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'      => '4',
                    'right'    => '4',
                    'bottom'   => '4',
                    'left'     => '4',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_flipbox_button_typography',
                'selector' => '{{WRAPPER}} .ua-flip-box-button',
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => '12',
                    'right'    => '24',
                    'bottom'   => '12',
                    'left'     => '24',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_button_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => '15',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_flipbox_button_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-flip-box-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-flip-box-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // HOVER STATE
        $this->start_controls_tab(
            'ua_flipbox_button_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_flipbox_button_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-flip-box-button:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_button_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#20087a',
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_flipbox_button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-flip-box-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER HELPER — Icon Output
     *========================================================================*/
    protected function render_icon( $icon_data, $location = 'front' ) {
        if ( empty( $icon_data['value'] ) ) {
            return;
        }

        if ( 'svg' === ( $icon_data['library'] ?? '' ) ) {
            echo '<span class="ua-flip-box-icon ua-flip-box-svg-icon ua-icon-' . esc_attr( $location ) . '">';
            Icons_Manager::render_icon( $icon_data, [ 'aria-hidden' => 'true' ] );
            echo '</span>';
        } else {
            echo '<span class="ua-flip-box-icon ua-icon-' . esc_attr( $location ) . '">';
            Icons_Manager::render_icon( $icon_data, [ 'aria-hidden' => 'true' ] );
            echo '</span>';
        }
    }

    /*==========================================================================
     * MAIN RENDER METHOD
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();

        $event_type        = ! empty( $settings['ua_flipbox_event_type'] ) ? $settings['ua_flipbox_event_type'] : 'hover';
        $flipbox_type      = ! empty( $settings['ua_flipbox_type'] ) ? $settings['ua_flipbox_type'] : 'animate-left';
        $height_mode       = ! empty( $settings['ua_flipbox_height_mode'] ) ? $settings['ua_flipbox_height_mode'] : 'fixed';
        $height_adjustment = ! empty( $settings['ua_flipbox_height_adjustment'] ) ? $settings['ua_flipbox_height_adjustment'] : 'maximum';
        $is_3d             = ! empty( $settings['ua_flipbox_3d'] ) ? 'ua-flip-box--3d' : '';

        // Front media setup
        $front_image = $settings['ua_flipbox_front_image'] ?? [];
        if ( ! empty( $front_image['id'] ) ) {
            $front_image['id'] = apply_filters( 'wpml_object_id', $front_image['id'], 'attachment', true );
            if ( $front_image['id'] ) {
                $front_image['url'] = wp_get_attachment_url( $front_image['id'] );
            }
        }
        $front_image_url = Group_Control_Image_Size::get_attachment_image_src( $front_image['id'] ?? 0, 'front_thumbnail', $settings );
        if ( empty( $front_image_url ) && ! empty( $front_image['url'] ) ) {
            $front_image_url = $front_image['url'];
        }

        // Back media setup
        $back_image = $settings['ua_flipbox_back_image'] ?? [];
        if ( ! empty( $back_image['id'] ) ) {
            $back_image['id'] = apply_filters( 'wpml_object_id', $back_image['id'], 'attachment', true );
            if ( $back_image['id'] ) {
                $back_image['url'] = wp_get_attachment_url( $back_image['id'] );
            }
        }
        $back_image_url = Group_Control_Image_Size::get_attachment_image_src( $back_image['id'] ?? 0, 'back_thumbnail', $settings );
        if ( empty( $back_image_url ) && ! empty( $back_image['url'] ) ) {
            $back_image_url = $back_image['url'];
        }

        // Link handling
        $link_type       = $settings['ua_flipbox_link_type'] ?? 'none';
        $has_link        = ( 'none' !== $link_type && ! empty( $settings['ua_flipbox_link']['url'] ) );
        $card_tag        = ( 'box' === $link_type && $has_link ) ? 'a' : 'div';
        $back_title_tag  = ! empty( $settings['ua_flipbox_back_title_tag'] ) ? Utils::validate_html_tag( $settings['ua_flipbox_back_title_tag'] ) : 'h3';
        $front_title_tag = ! empty( $settings['ua_flipbox_front_title_tag'] ) ? Utils::validate_html_tag( $settings['ua_flipbox_front_title_tag'] ) : 'h3';

        // Wrapper attributes
        $this->add_render_attribute( 'wrapper', 'class', [
            'ua-flip-box-container',
            'ua-' . esc_attr( $flipbox_type ),
            'ua-flip-box-' . esc_attr( $event_type ),
            'fixed' === $height_mode ? 'ua-flip-box-fixed-height' : 'ua-flip-box-auto-height',
            'maximum' === $height_adjustment ? 'ua-flipbox-max' : 'ua-flipbox-dynamic',
            $is_3d,
        ] );

        // Card attributes
        $this->add_render_attribute( 'card', 'class', 'ua-flip-box-card' );
        if ( 'box' === $link_type && $has_link ) {
            $this->add_link_attributes( 'card', $settings['ua_flipbox_link'] );
        }

        // Back Title attributes
        $this->add_render_attribute( 'back-title', 'class', 'ua-flip-box-title' );
        if ( 'title' === $link_type && $has_link ) {
            $this->add_render_attribute( 'back-title-link', 'class', 'ua-flip-box-linked-title' );
            $this->add_link_attributes( 'back-title-link', $settings['ua_flipbox_link'] );
        }

        // Button attributes
        if ( 'button' === $link_type && $has_link ) {
            $this->add_render_attribute( 'button', 'class', 'ua-flip-box-button' );
            $this->add_link_attributes( 'button', $settings['ua_flipbox_link'] );
        }

        // Image shape class
        $image_shape_class = '';
        if ( ! empty( $settings['ua_flipbox_image_style_type'] ) && 'circle' === $settings['ua_flipbox_image_style_type'] ) {
            $image_shape_class = ' ua-flipbox-img-circle';
        }
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <<?php echo esc_html( $card_tag ); ?> <?php $this->print_render_attribute_string( 'card' ); ?>>

                <!-- FRONT SIDE -->
                <div class="ua-flip-box-front<?php echo esc_attr( $image_shape_class ); ?>">
                    <div class="ua-flip-box-inner">
                        <?php if ( 'template' === ( $settings['ua_flipbox_front_content_type'] ?? 'content' ) ) : ?>
                            <?php
                            $template_id = $settings['ua_flipbox_front_template'] ?? 0;
                            if ( ! empty( $template_id ) ) {
                                echo Plugin::$instance->frontend->get_builder_content_for_display( $template_id );
                            }
                            ?>
                        <?php else : ?>
                            <?php if ( 'icon' === ( $settings['ua_flipbox_front_media_type'] ?? 'icon' ) && ! empty( $settings['ua_flipbox_front_icon']['value'] ) ) : ?>
                                <div class="ua-flip-box-media">
                                    <div class="ua-flip-box-icon-wrap">
                                        <?php $this->render_icon( $settings['ua_flipbox_front_icon'], 'front' ); ?>
                                    </div>
                                </div>
                            <?php elseif ( 'img' === ( $settings['ua_flipbox_front_media_type'] ?? 'icon' ) && ! empty( $front_image_url ) ) : ?>
                                <div class="ua-flip-box-media">
                                    <div class="ua-flip-box-image-wrap">
                                        <img src="<?php echo esc_url( $front_image_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $front_image['id'] ?? 0, '_wp_attachment_image_alt', true ) ); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $settings['ua_flipbox_front_title'] ) ) : ?>
                                <<?php echo esc_html( $front_title_tag ); ?> class="ua-flip-box-title">
                                    <?php echo esc_html( $settings['ua_flipbox_front_title'] ); ?>
                                </<?php echo esc_html( $front_title_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( ! empty( $settings['ua_flipbox_front_text'] ) ) : ?>
                                <div class="ua-flip-box-desc">
                                    <?php echo $this->parse_text_editor( $settings['ua_flipbox_front_text'] ); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- BACK SIDE -->
                <div class="ua-flip-box-back<?php echo esc_attr( $image_shape_class ); ?>">
                    <div class="ua-flip-box-inner">
                        <?php if ( 'template' === ( $settings['ua_flipbox_back_content_type'] ?? 'content' ) ) : ?>
                            <?php
                            $back_template_id = $settings['ua_flipbox_back_template'] ?? 0;
                            if ( ! empty( $back_template_id ) ) {
                                echo Plugin::$instance->frontend->get_builder_content_for_display( $back_template_id );
                            }
                            ?>
                        <?php else : ?>
                            <?php if ( 'icon' === ( $settings['ua_flipbox_back_media_type'] ?? 'icon' ) && ! empty( $settings['ua_flipbox_back_icon']['value'] ) ) : ?>
                                <div class="ua-flip-box-media">
                                    <div class="ua-flip-box-icon-wrap">
                                        <?php $this->render_icon( $settings['ua_flipbox_back_icon'], 'back' ); ?>
                                    </div>
                                </div>
                            <?php elseif ( 'img' === ( $settings['ua_flipbox_back_media_type'] ?? 'icon' ) && ! empty( $back_image_url ) ) : ?>
                                <div class="ua-flip-box-media">
                                    <div class="ua-flip-box-image-wrap">
                                        <img src="<?php echo esc_url( $back_image_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $back_image['id'] ?? 0, '_wp_attachment_image_alt', true ) ); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $settings['ua_flipbox_back_title'] ) ) : ?>
                                <<?php echo esc_html( $back_title_tag ); ?> <?php $this->print_render_attribute_string( 'back-title' ); ?>>
                                    <?php if ( 'title' === $link_type && $has_link ) : ?>
                                        <a <?php $this->print_render_attribute_string( 'back-title-link' ); ?>>
                                            <?php echo esc_html( $settings['ua_flipbox_back_title'] ); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo esc_html( $settings['ua_flipbox_back_title'] ); ?>
                                    <?php endif; ?>
                                </<?php echo esc_html( $back_title_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( ! empty( $settings['ua_flipbox_back_text'] ) ) : ?>
                                <div class="ua-flip-box-desc">
                                    <?php echo $this->parse_text_editor( $settings['ua_flipbox_back_text'] ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( 'button' === $link_type && ! empty( $settings['ua_flipbox_button_text'] ) ) : ?>
                                <a <?php $this->print_render_attribute_string( 'button' ); ?>>
                                    <?php if ( 'before' === ( $settings['ua_flipbox_button_icon_position'] ?? 'after' ) && ! empty( $settings['ua_flipbox_button_icon']['value'] ) ) : ?>
                                        <span class="ua-btn-icon ua-btn-icon-before">
                                            <?php Icons_Manager::render_icon( $settings['ua_flipbox_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                        </span>
                                    <?php endif; ?>

                                    <span class="ua-btn-text"><?php echo esc_html( $settings['ua_flipbox_button_text'] ); ?></span>

                                    <?php if ( 'after' === ( $settings['ua_flipbox_button_icon_position'] ?? 'after' ) && ! empty( $settings['ua_flipbox_button_icon']['value'] ) ) : ?>
                                        <span class="ua-btn-icon ua-btn-icon-after">
                                            <?php Icons_Manager::render_icon( $settings['ua_flipbox_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </<?php echo esc_html( $card_tag ); ?>>
        </div>
        <?php
    }
}
