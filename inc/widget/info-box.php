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
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons Info Box Widget
 *
 * Feature-rich Info Box widget with multiple media options (Icon, Image, Number),
 * flexible layouts (Top, Bottom, Left, Right), rich typography, badges (Corner Ribbon,
 * Circle, Flag), custom buttons, and complete card clickability.
 *
 * @since 1.2.0
 */
class Info_Box extends Base {

    /**
     * Constructor — register style dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-info-box',
            ULTRA_ADDONS_ASSETS . 'css/widgets/info-box.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-info-box' );
    }

    public function get_style_depends() {
        return [ 'ultraaddons-info-box' ];
    }

    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'info', 'infobox', 'info box', 'icon box', 'service', 'card', 'badge', 'feature' ];
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_media_controls();
        $this->content_body_controls();
        $this->content_badge_controls();
        $this->content_button_controls();

        // Style Tab
        $this->style_container_controls();
        $this->style_media_controls();
        $this->style_badge_controls();
        $this->style_title_controls();
        $this->style_description_controls();
        $this->style_button_controls();
    }

    /*==========================================================================
     * CONTENT TAB — Media Settings (Icon / Image / Number)
     *========================================================================*/
    protected function content_media_controls() {
        $this->start_controls_section(
            '_ua_infobox_media_section',
            [
                'label' => esc_html__( 'Media / Icon', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_infobox_media_layout',
            [
                'label'   => esc_html__( 'Placement', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'    => esc_html__( 'Image / Icon On Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Image / Icon On Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Image / Icon On Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Image / Icon On Right', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_media_type',
            [
                'label'   => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'icon',
                'options' => [
                    'none'   => [
                        'title' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'icon'   => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                    'image'  => [
                        'title' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-image',
                    ],
                    'number' => [
                        'title' => esc_html__( 'Number', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-number-field',
                    ],
                ],
            ]
        );

        // Vertical Alignment for Left/Right placement
        $this->add_responsive_control(
            '_ua_infobox_media_valign',
            [
                'label'     => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'flex-start',
                'condition' => [
                    '_ua_infobox_media_layout' => [ 'left', 'right' ],
                    '_ua_infobox_media_type!'  => 'none',
                ],
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-media' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        // Horizontal Alignment for Top/Bottom placement
        $this->add_responsive_control(
            '_ua_infobox_media_halign',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'condition' => [
                    '_ua_infobox_media_layout' => [ 'top', 'bottom' ],
                    '_ua_infobox_media_type!'  => 'none',
                ],
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-media' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // Icon Selector
        $this->add_control(
            '_ua_infobox_icon',
            [
                'label'            => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => '_ua_infobox_icon_old',
                'default'          => [
                    'value'   => 'fas fa-rocket',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    '_ua_infobox_media_type' => 'icon',
                ],
            ]
        );

        // Image Selector
        $this->add_control(
            '_ua_infobox_image',
            [
                'label'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    '_ua_infobox_media_type' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => '_ua_infobox_image_size',
                'default'   => 'full',
                'condition' => [
                    '_ua_infobox_media_type' => 'image',
                ],
            ]
        );

        // Number Control
        $this->add_control(
            '_ua_infobox_number_text',
            [
                'label'       => esc_html__( 'Number', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '01',
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_infobox_media_type' => 'number',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Content Settings
     *========================================================================*/
    protected function content_body_controls() {
        $this->start_controls_section(
            '_ua_infobox_content_section',
            [
                'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
            ]
        );

        // Title
        $this->add_control(
            '_ua_infobox_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Creative Info Box', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_infobox_title_tag',
            [
                'label'   => esc_html__( 'Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'span' => 'SPAN',
                    'p'    => 'P',
                    'div'  => 'DIV',
                ],
            ]
        );

        // Sub Title Switcher
        $this->add_control(
            '_ua_infobox_show_subtitle',
            [
                'label'        => esc_html__( 'Sub Title', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            '_ua_infobox_subtitle_position',
            [
                'label'     => esc_html__( 'Sub Title Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'column',
                'options'   => [
                    'column-reverse' => [
                        'title' => esc_html__( 'Above Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-arrow-up',
                    ],
                    'column'         => [
                        'title' => esc_html__( 'Below Title', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-arrow-down',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-title-wrap' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_subtitle',
            [
                'label'       => esc_html__( 'Sub Title Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Explore Our Features', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_subtitle_tag',
            [
                'label'     => esc_html__( 'Sub Title HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'span',
                'options'   => [
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'span' => 'SPAN',
                    'p'    => 'P',
                    'div'  => 'DIV',
                ],
                'condition' => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        // Description Switcher & Content
        $this->add_control(
            '_ua_infobox_show_desc',
            [
                'label'        => esc_html__( 'Show Description', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            '_ua_infobox_description',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Provide a brief, compelling summary or feature details that inform and engage your visitors.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    '_ua_infobox_show_desc' => 'yes',
                ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            '_ua_infobox_content_align',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
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
                'default'      => 'center',
                'prefix_class' => 'ua-%salign-',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Badge Settings (Corner Ribbon, Circle, Flag)
     *========================================================================*/
    protected function content_badge_controls() {
        $this->start_controls_section(
            '_ua_infobox_badge_section',
            [
                'label' => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_infobox_badge_style',
            [
                'label'   => esc_html__( 'Badge Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'corner',
                'options' => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'corner' => esc_html__( 'Corner Ribbon', 'ultraaddons-elementor-lite' ),
                    'circle' => esc_html__( 'Circle Badge', 'ultraaddons-elementor-lite' ),
                    'flag'   => esc_html__( 'Flag / Bookmark', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_badge_title',
            [
                'label'       => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'HOT', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    '_ua_infobox_badge_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_badge_pos',
            [
                'label'     => esc_html__( 'Horizontal Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'right',
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    '_ua_infobox_badge_style!' => 'none',
                ],
            ]
        );

        // Size for Circle Badge
        $this->add_responsive_control(
            '_ua_infobox_badge_circle_size',
            [
                'label'      => esc_html__( 'Badge Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 50, 'unit' => 'px' ],
                'range'      => [ 'px' => [ 'min' => 30, 'max' => 120 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox-badge-circle .ua-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_infobox_badge_style' => 'circle',
                ],
            ]
        );

        // Distance / Offset for Corner and Flag
        $this->add_responsive_control(
            '_ua_infobox_badge_distance',
            [
                'label'      => esc_html__( 'Distance / Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 30, 'unit' => 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox-badge-corner .ua-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
                    '{{WRAPPER}} .ua-infobox-badge-flag'                   => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_infobox_badge_style' => [ 'corner', 'flag' ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB — Button & Link Settings
     *========================================================================*/
    protected function content_button_controls() {
        $this->start_controls_section(
            '_ua_infobox_button_section',
            [
                'label' => esc_html__( 'Button & Link', 'ultraaddons-elementor-lite' ),
            ]
        );

        // Clickable Card Switcher
        $this->add_control(
            '_ua_infobox_card_clickable',
            [
                'label'        => esc_html__( 'Make Entire Card Clickable', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
                'description'  => esc_html__( 'Turns the entire info box into a clickable link.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_infobox_card_link',
            [
                'label'       => esc_html__( 'Card Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => 'https://your-link.com',
                'default'     => [ 'url' => '#' ],
                'condition'   => [
                    '_ua_infobox_card_clickable' => 'yes',
                ],
            ]
        );

        // Show Button
        $this->add_control(
            '_ua_infobox_show_btn',
            [
                'label'        => esc_html__( 'Show Action Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default'     => esc_html__( 'Read More', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    '_ua_infobox_show_btn'        => 'yes',
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_btn_link',
            [
                'label'       => esc_html__( 'Button Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => 'https://your-link.com',
                'default'     => [ 'url' => '#' ],
                'condition'   => [
                    '_ua_infobox_show_btn'        => 'yes',
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_btn_icon',
            [
                'label'       => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-arrow-right',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    '_ua_infobox_show_btn'        => 'yes',
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_btn_icon_position',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'left'  => esc_html__( 'Before Text', 'ultraaddons-elementor-lite' ),
                    'right' => esc_html__( 'After Text', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_infobox_show_btn'        => 'yes',
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_btn_icon_spacing',
            [
                'label'     => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [ 'px' => [ 'max' => 50 ] ],
                'default'   => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-btn-icon-right' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-infobox-btn-icon-left'  => 'margin-right: {{SIZE}}px;',
                ],
                'condition' => [
                    '_ua_infobox_show_btn'        => 'yes',
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Container Styling
     *========================================================================*/
    protected function style_container_controls() {
        $this->start_controls_section(
            '_ua_infobox_container_style_section',
            [
                'label' => esc_html__( 'Container Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( '_ua_infobox_box_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            '_ua_infobox_box_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_infobox_box_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-infobox',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_infobox_box_border',
                'selector' => '{{WRAPPER}} .ua-infobox',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_box_shadow',
                'selector' => '{{WRAPPER}} .ua-infobox',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            '_ua_infobox_box_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_infobox_box_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-infobox:hover, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox',
            ]
        );

        $this->add_control(
            '_ua_infobox_box_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_box_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-infobox:hover, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            '_ua_infobox_box_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_box_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => '30',
                    'right'  => '30',
                    'bottom' => '30',
                    'left'   => '30',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_box_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Media / Icon Styling
     *========================================================================*/
    protected function style_media_controls() {
        $this->start_controls_section(
            '_ua_infobox_media_style_section',
            [
                'label'     => esc_html__( 'Media / Icon', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_infobox_media_type!' => 'none',
                ],
            ]
        );

        // Icon Size / Image Size
        $this->add_responsive_control(
            '_ua_infobox_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [ 'size' => 40, 'unit' => 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_infobox_media_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_image_width',
            [
                'label'      => esc_html__( 'Image Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'size' => 80, 'unit' => 'px' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 500 ],
                    '%'  => [ 'min' => 1, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_infobox_media_type' => 'image',
                ],
            ]
        );

        // Background Shape
        $this->add_control(
            '_ua_infobox_icon_shape',
            [
                'label'   => esc_html__( 'Background Shape', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'circle' => esc_html__( 'Circle', 'ultraaddons-elementor-lite' ),
                    'radius' => esc_html__( 'Rounded Box', 'ultraaddons-elementor-lite' ),
                    'square' => esc_html__( 'Square', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-shape-',
            ]
        );

        // Icon Box Size
        $this->add_responsive_control(
            '_ua_infobox_icon_box_size',
            [
                'label'      => esc_html__( 'Box Dimensions', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [ 'size' => 80, 'unit' => 'px' ],
                'range'      => [ 'px' => [ 'min' => 20, 'max' => 300 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_infobox_icon_shape!' => 'none',
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_infobox_media_tabs' );

        // Media Normal Tab
        $this->start_controls_tab(
            '_ua_infobox_media_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap i'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap svg'    => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox .ua-infobox-number'           => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_infobox_icon_border',
                'selector' => '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_icon_shadow',
                'selector' => '{{WRAPPER}} .ua-infobox .ua-infobox-icon-wrap',
            ]
        );

        $this->end_controls_tab();

        // Media Hover Tab
        $this->start_controls_tab(
            '_ua_infobox_media_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_icon_color_hover',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-icon-wrap i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-icon-wrap svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-number'        => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_icon_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_icon_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-icon-wrap' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_icon_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-infobox:hover .ua-infobox-icon-wrap',
            ]
        );

        $this->add_control(
            '_ua_infobox_icon_hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Number Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => '_ua_infobox_number_typography',
                'selector'  => '{{WRAPPER}} .ua-infobox .ua-infobox-number',
                'separator' => 'before',
                'condition' => [
                    '_ua_infobox_media_type' => 'number',
                ],
            ]
        );

        // Media Spacing / Margin
        $this->add_responsive_control(
            '_ua_infobox_media_margin',
            [
                'label'      => esc_html__( 'Media Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'separator'  => 'before',
                'default'    => [
                    'top'    => '0',
                    'right'  => '0',
                    'bottom' => '15',
                    'left'   => '0',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Badge Styling
     *========================================================================*/
    protected function style_badge_controls() {
        $this->start_controls_section(
            '_ua_infobox_badge_style_section',
            [
                'label'     => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_infobox_badge_style!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_infobox_badge_typography',
                'selector' => '{{WRAPPER}} .ua-infobox-badge .ua-badge-inner',
            ]
        );

        $this->start_controls_tabs( '_ua_infobox_badge_style_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            '_ua_infobox_badge_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_badge_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-badge .ua-badge-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_badge_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ff4757',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-badge .ua-badge-inner'           => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox-badge.ua-badge-flag:before'      => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => '_ua_infobox_badge_border',
                'selector'  => '{{WRAPPER}} .ua-infobox-badge .ua-badge-inner',
                'condition' => [
                    '_ua_infobox_badge_style!' => 'corner',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_badge_shadow',
                'selector' => '{{WRAPPER}} .ua-infobox-badge .ua-badge-inner',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            '_ua_infobox_badge_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_badge_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-badge .ua-badge-inner, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-badge .ua-badge-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_badge_bg_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-badge .ua-badge-inner, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-badge .ua-badge-inner' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-badge.ua-badge-flag:before, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-badge.ua-badge-flag:before' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_badge_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-infobox:hover .ua-infobox-badge .ua-badge-inner, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-badge .ua-badge-inner',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Title & Sub Title Styling
     *========================================================================*/
    protected function style_title_controls() {
        $this->start_controls_section(
            '_ua_infobox_title_style_section',
            [
                'label' => esc_html__( 'Title & Sub Title', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Title Heading
        $this->add_control(
            '_ua_infobox_title_style_label',
            [
                'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->start_controls_tabs( '_ua_infobox_title_tabs' );

        $this->start_controls_tab(
            '_ua_infobox_title_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_infobox_title_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_title_color_hover',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-title, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_infobox_title_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ],
                'selector' => '{{WRAPPER}} .ua-infobox .ua-infobox-title',
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_title_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Sub Title Styling
        $this->add_control(
            '_ua_infobox_subtitle_style_label',
            [
                'label'     => esc_html__( 'Sub Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            '_ua_infobox_subtitle_tabs',
            [
                'condition' => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            '_ua_infobox_subtitle_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_subtitle_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#13c392',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_infobox_subtitle_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_subtitle_color_hover',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-subtitle, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => '_ua_infobox_subtitle_typography',
                'global'    => [ 'default' => Global_Typography::TYPOGRAPHY_SECONDARY ],
                'selector'  => '{{WRAPPER}} .ua-infobox .ua-infobox-subtitle',
                'condition' => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_subtitle_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    '_ua_infobox_show_subtitle' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Description Styling
     *========================================================================*/
    protected function style_description_controls() {
        $this->start_controls_section(
            '_ua_infobox_desc_style_section',
            [
                'label'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_infobox_show_desc' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_infobox_desc_tabs' );

        $this->start_controls_tab(
            '_ua_infobox_desc_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_desc_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-desc, {{WRAPPER}} .ua-infobox .ua-infobox-desc p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_ua_infobox_desc_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_desc_color_hover',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox:hover .ua-infobox-desc, {{WRAPPER}} .ua-infobox:hover .ua-infobox-desc p, {{WRAPPER}} .ua-infobox-clickable-wrap:hover .ua-infobox-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_infobox_desc_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_TEXT ],
                'selector' => '{{WRAPPER}} .ua-infobox .ua-infobox-desc, {{WRAPPER}} .ua-infobox .ua-infobox-desc p',
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_desc_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox .ua-infobox-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Button Styling
     *========================================================================*/
    protected function style_button_controls() {
        $this->start_controls_section(
            '_ua_infobox_btn_style_section',
            [
                'label'     => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_ua_infobox_show_btn'        => 'yes',
                    '_ua_infobox_card_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_infobox_btn_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_ACCENT ],
                'selector' => '{{WRAPPER}} .ua-infobox-btn',
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_btn_icon_size_style',
            [
                'label'     => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [ 'px' => [ 'min' => 8, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-btn i'   => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-infobox-btn svg' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs( '_ua_infobox_btn_tabs' );

        // Button Normal Tab
        $this->start_controls_tab(
            '_ua_infobox_btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-btn'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox-btn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_infobox_btn_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-infobox-btn',
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color'      => [ 'default' => '#13c392' ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_infobox_btn_border',
                'selector' => '{{WRAPPER}} .ua-infobox-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_btn_shadow',
                'selector' => '{{WRAPPER}} .ua-infobox-btn',
            ]
        );

        $this->end_controls_tab();

        // Button Hover Tab
        $this->start_controls_tab(
            '_ua_infobox_btn_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            '_ua_infobox_btn_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-btn:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-infobox-btn:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_infobox_btn_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-infobox-btn:hover',
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color'      => [ 'default' => '#0eb384' ],
                ],
            ]
        );

        $this->add_control(
            '_ua_infobox_btn_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-infobox-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_infobox_btn_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-infobox-btn:hover',
            ]
        );

        $this->add_control(
            '_ua_infobox_btn_hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            '_ua_infobox_btn_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'separator'  => 'before',
                'default'    => [
                    'top'    => '4',
                    'right'  => '4',
                    'bottom' => '4',
                    'left'   => '4',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_btn_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'    => '10',
                    'right'  => '22',
                    'bottom' => '10',
                    'left'   => '22',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_infobox_btn_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-infobox-btn-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER — Frontend & Editor Output
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();

        $media_type   = ! empty( $settings['_ua_infobox_media_type'] ) ? $settings['_ua_infobox_media_type'] : 'icon';
        $media_layout = ! empty( $settings['_ua_infobox_media_layout'] ) ? $settings['_ua_infobox_media_layout'] : 'top';
        $is_clickable = ( ! empty( $settings['_ua_infobox_card_clickable'] ) && $settings['_ua_infobox_card_clickable'] === 'yes' );

        // Badge settings
        $badge_style = ! empty( $settings['_ua_infobox_badge_style'] ) ? $settings['_ua_infobox_badge_style'] : 'none';
        $badge_pos   = ! empty( $settings['_ua_infobox_badge_pos'] ) ? $settings['_ua_infobox_badge_pos'] : 'right';
        $badge_title = ! empty( $settings['_ua_infobox_badge_title'] ) ? $settings['_ua_infobox_badge_title'] : '';
        $has_badge   = ( $badge_style !== 'none' && ! empty( $badge_title ) );

        // Wrap Classes
        $wrap_classes = [ 'ua-infobox-wrap' ];
        if ( $has_badge && $badge_style === 'corner' ) {
            $wrap_classes[] = 'ua-has-corner-badge';
        }

        // Box Classes
        $box_classes = [ 'ua-infobox', 'ua-media-' . esc_attr( $media_layout ) ];

        // Add link attributes if whole box is clickable
        if ( $is_clickable && ! empty( $settings['_ua_infobox_card_link']['url'] ) ) {
            $this->add_link_attributes( 'card_link', $settings['_ua_infobox_card_link'] );
            $this->add_render_attribute( 'card_link', 'class', 'ua-infobox-clickable-wrap' );
        }

        // Allowed HTML Tags
        $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'p', 'div' ];
        $title_tag    = ! empty( $settings['_ua_infobox_title_tag'] ) && in_array( $settings['_ua_infobox_title_tag'], $allowed_tags, true )
            ? $settings['_ua_infobox_title_tag']
            : 'h3';
        $subtitle_tag = ! empty( $settings['_ua_infobox_subtitle_tag'] ) && in_array( $settings['_ua_infobox_subtitle_tag'], $allowed_tags, true )
            ? $settings['_ua_infobox_subtitle_tag']
            : 'span';

        // Media Icon Migration Check
        $icon_migrated = isset( $settings['__fa4_migrated']['_ua_infobox_icon'] );
        $icon_is_new   = empty( $settings['_ua_infobox_icon_old'] );
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrap_classes ) ); ?>">
            <?php if ( $is_clickable ) : ?>
                <a <?php $this->print_render_attribute_string( 'card_link' ); ?>>
            <?php endif; ?>

            <div class="<?php echo esc_attr( implode( ' ', $box_classes ) ); ?>">

                <?php if ( $has_badge ) : ?>
                    <div class="ua-infobox-badge ua-infobox-badge-<?php echo esc_attr( $badge_style ); ?> ua-infobox-badge-<?php echo esc_attr( $badge_pos ); ?>">
                        <div class="ua-badge-inner"><?php echo esc_html( $badge_title ); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ( $media_type !== 'none' ) : ?>
                    <div class="ua-infobox-media">
                        <div class="ua-infobox-icon-wrap">
                            <?php if ( $media_type === 'icon' ) : ?>
                                <?php if ( $icon_migrated || $icon_is_new ) : ?>
                                    <?php Icons_Manager::render_icon( $settings['_ua_infobox_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php elseif ( ! empty( $settings['_ua_infobox_icon_old'] ) ) : ?>
                                    <i class="<?php echo esc_attr( $settings['_ua_infobox_icon_old'] ); ?>"></i>
                                <?php endif; ?>
                            <?php elseif ( $media_type === 'image' && ! empty( $settings['_ua_infobox_image']['url'] ) ) : ?>
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, '_ua_infobox_image_size', '_ua_infobox_image' ); ?>
                            <?php elseif ( $media_type === 'number' ) : ?>
                                <span class="ua-infobox-number"><?php echo esc_html( $settings['_ua_infobox_number_text'] ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="ua-infobox-content">

                    <?php if ( ! empty( $settings['_ua_infobox_title'] ) || ! empty( $settings['_ua_infobox_subtitle'] ) ) : ?>
                        <div class="ua-infobox-title-wrap">

                            <?php if ( ! empty( $settings['_ua_infobox_show_subtitle'] ) && $settings['_ua_infobox_show_subtitle'] === 'yes' && ! empty( $settings['_ua_infobox_subtitle'] ) ) : ?>
                                <<?php echo esc_attr( $subtitle_tag ); ?> class="ua-infobox-subtitle">
                                    <?php echo esc_html( $settings['_ua_infobox_subtitle'] ); ?>
                                </<?php echo esc_attr( $subtitle_tag ); ?>>
                            <?php endif; ?>

                            <?php if ( ! empty( $settings['_ua_infobox_title'] ) ) : ?>
                                <<?php echo esc_attr( $title_tag ); ?> class="ua-infobox-title">
                                    <?php echo esc_html( $settings['_ua_infobox_title'] ); ?>
                                </<?php echo esc_attr( $title_tag ); ?>>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['_ua_infobox_show_desc'] ) && $settings['_ua_infobox_show_desc'] === 'yes' && ! empty( $settings['_ua_infobox_description'] ) ) : ?>
                        <div class="ua-infobox-desc">
                            <?php echo wp_kses_post( $settings['_ua_infobox_description'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! $is_clickable && ! empty( $settings['_ua_infobox_show_btn'] ) && $settings['_ua_infobox_show_btn'] === 'yes' ) : ?>
                        <?php
                        $btn_classes = [ 'ua-infobox-btn' ];
                        if ( ! empty( $settings['_ua_infobox_btn_hover_animation'] ) ) {
                            $btn_classes[] = 'elementor-animation-' . esc_attr( $settings['_ua_infobox_btn_hover_animation'] );
                        }
                        $this->add_render_attribute( 'infobox_btn', 'class', $btn_classes );

                        if ( ! empty( $settings['_ua_infobox_btn_link']['url'] ) ) {
                            $this->add_link_attributes( 'infobox_btn', $settings['_ua_infobox_btn_link'] );
                        }

                        $btn_icon_pos = ! empty( $settings['_ua_infobox_btn_icon_position'] ) ? $settings['_ua_infobox_btn_icon_position'] : 'right';
                        $has_btn_icon = ! empty( $settings['_ua_infobox_btn_icon']['value'] );
                        ?>
                        <div class="ua-infobox-btn-wrap">
                            <a <?php $this->print_render_attribute_string( 'infobox_btn' ); ?>>
                                <?php if ( $has_btn_icon && $btn_icon_pos === 'left' ) : ?>
                                    <span class="ua-infobox-btn-icon-left">
                                        <?php Icons_Manager::render_icon( $settings['_ua_infobox_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ( ! empty( $settings['_ua_infobox_btn_text'] ) ) : ?>
                                    <span class="ua-infobox-btn-text"><?php echo esc_html( $settings['_ua_infobox_btn_text'] ); ?></span>
                                <?php endif; ?>

                                <?php if ( $has_btn_icon && $btn_icon_pos === 'right' ) : ?>
                                    <span class="ua-infobox-btn-icon-right">
                                        <?php Icons_Manager::render_icon( $settings['_ua_infobox_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>

            </div>

            <?php if ( $is_clickable ) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
}
