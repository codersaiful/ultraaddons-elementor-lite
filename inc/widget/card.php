<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Modern Card Widget
 *
 * An exceptionally versatile, high-converting presentation card component
 * for showcasing team members, products, service features, and portfolio items.
 *
 * @since 1.1.0.8
 * @package UltraAddons
 */
class Card extends Base {

    /**
     * Constructor: Register widget styles and scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-card.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-card-script',
            ULTRA_ADDONS_ASSETS . 'js/frontend-card.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    public function get_keywords() {
        return [ 'ultraaddons', 'card', 'profile', 'product', 'team', 'feature', 'info box' ];
    }

    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [ 'ultraaddons-card-script' ] );
    }

    protected function register_controls() {
        // CONTENT TAB
        $this->register_setup_controls();
        $this->register_media_controls();
        $this->register_content_controls();
        $this->register_button_controls();
        $this->register_price_controls();
        $this->register_social_controls();

        // STYLE TAB
        $this->register_container_style_controls();
        $this->register_media_style_controls();
        $this->register_title_style_controls();
        $this->register_desc_style_controls();
        $this->register_button_style_controls();
        $this->register_price_style_controls();
        $this->register_social_style_controls();
    }

    /* =========================================================================
       CONTENT TAB: Section 1 - Card Setup & Presets
       ========================================================================= */
    protected function register_setup_controls() {
        $this->start_controls_section(
            'section_ua_card_setup',
            [
                'label' => esc_html__( 'Card Setup & Presets', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'preset_style',
            [
                'label'       => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'clean-classic',
                'options'     => [
                    'clean-classic'      => esc_html__( 'Clean Classic (Default)', 'ultraaddons-elementor-lite' ),
                    'modern-profile'     => esc_html__( 'Modern Profile (Team / Author)', 'ultraaddons-elementor-lite' ),
                    'product-card'       => esc_html__( 'Product Showcase (eCommerce)', 'ultraaddons-elementor-lite' ),
                    'minimal-horizontal' => esc_html__( 'Minimal Horizontal (Row Layout)', 'ultraaddons-elementor-lite' ),
                    'soft-shadow'        => esc_html__( 'Soft Shadow (Elevated Modern)', 'ultraaddons-elementor-lite' ),
                ],
                'description' => esc_html__( 'Pick a ready-made design preset or customize every detail below.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_responsive_control(
            'layout_direction',
            [
                'label'        => esc_html__( 'Layout Direction', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'column' => [
                        'title' => esc_html__( 'Vertical (Stacked)', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'row'    => [
                        'title' => esc_html__( 'Horizontal (Side by Side)', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => 'column',
                'prefix_class' => 'ua-card-dir%s-',
            ]
        );

        $this->add_responsive_control(
            'reverse_order',
            [
                'label'        => esc_html__( 'Reverse Media Position', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'prefix_class' => 'ua-card-reverse-',
                'description'  => esc_html__( 'Flips media to the bottom (in vertical) or right side (in horizontal).', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label'        => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
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
                'default'      => 'left',
                'selectors'    => [
                    '{{WRAPPER}} .ua-card-item .ua-card-body' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 2 - Media & Badge
       ========================================================================= */
    protected function register_media_controls() {
        $this->start_controls_section(
            'section_ua_card_media',
            [
                'label' => esc_html__( 'Media & Badge', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $placeholder_image = ULTRA_ADDONS_URL . 'assets/images/card.png';
        $this->add_control(
            'card_image',
            [
                'label'   => esc_html__( 'Card Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $placeholder_image,
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'image_size',
                'default' => 'large',
            ]
        );

        $this->add_control(
            'image_hover_effect',
            [
                'label'        => esc_html__( 'Image Hover Effect', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'zoom',
                'options'      => [
                    'none' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'zoom' => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                    'glow' => esc_html__( 'Brightness Glow', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-card-img-effect-',
            ]
        );

        // Badge Options
        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show Badge / Tag', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label'       => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Featured', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. HOT, NEW, 30% OFF', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label'     => esc_html__( 'Badge Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top-right',
                'options'   => [
                    'top-left'  => esc_html__( 'Top Left', 'ultraaddons-elementor-lite' ),
                    'top-right' => esc_html__( 'Top Right', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 3 - Card Content
       ========================================================================= */
    protected function register_content_controls() {
        $this->start_controls_section(
            'section_ua_card_content',
            [
                'label' => esc_html__( 'Card Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'card_subtitle',
            [
                'label'       => esc_html__( 'Subtitle / Category', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Product Design Lead', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Designation or Category', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'subtitle_tag',
            [
                'label'   => esc_html__( 'Subtitle Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'span' => 'span',
                    'p'    => 'p',
                    'div'  => 'div',
                ],
            ]
        );

        $this->add_control(
            'card_title',
            [
                'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Alex Morgan', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter card headline or name', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'title_tag',
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
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
            ]
        );

        $this->add_control(
            'title_link',
            [
                'label'       => esc_html__( 'Title Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'card_description',
            [
                'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 4,
                'default'     => esc_html__( 'Passionate about crafting intuitive, user-centric interfaces and building scalable design systems that deliver genuine business impact.', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Enter card summary or bio...', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 4 - Action Button
       ========================================================================= */
    protected function register_button_controls() {
        $this->start_controls_section(
            'section_ua_card_button',
            [
                'label' => esc_html__( 'Action Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'View Profile', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Leave empty to hide button', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label'       => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label'     => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'after',
                'options'   => [
                    'before' => esc_html__( 'Before Text', 'ultraaddons-elementor-lite' ),
                    'after'  => esc_html__( 'After Text', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'button_icon[value]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_spacing',
            [
                'label'      => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-cta-btn .ua-card-btn-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-card-cta-btn .ua-card-btn-icon-after'  => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'button_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 5 - Price & Wishlist
       ========================================================================= */
    protected function register_price_controls() {
        $this->start_controls_section(
            'section_ua_card_price',
            [
                'label' => esc_html__( 'Price & Wishlist', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_pricing',
            [
                'label'        => esc_html__( 'Show Price & Wishlist', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Ideal for products, merchandise, or premium paid services.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'price_regular',
            [
                'label'       => esc_html__( 'Regular Price (Original)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '$120',
                'placeholder' => esc_html__( 'e.g. $120 (Strikethrough)', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_pricing' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'price_sale',
            [
                'label'       => esc_html__( 'Sale Price (Current)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '$89',
                'placeholder' => esc_html__( 'e.g. $89', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_pricing' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label'        => esc_html__( 'Show Wishlist Icon', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'show_pricing' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_icon',
            [
                'label'       => esc_html__( 'Wishlist Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-heart',
                    'library' => 'solid',
                ],
                'condition'   => [
                    'show_pricing'  => 'yes',
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_link',
            [
                'label'       => esc_html__( 'Wishlist Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-shop.com/wishlist', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_pricing'  => 'yes',
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       CONTENT TAB: Section 6 - Social Profiles
       ========================================================================= */
    protected function register_social_controls() {
        $this->start_controls_section(
            'section_ua_card_socials',
            [
                'label' => esc_html__( 'Social Profiles', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_socials',
            [
                'label'        => esc_html__( 'Show Social Icons', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Perfect for team member profiles and personal portfolios.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_label',
            [
                'label'   => esc_html__( 'Platform Name', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'LinkedIn', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'social_icon',
            [
                'label'   => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fab fa-linkedin-in',
                    'library' => 'brands',
                ],
            ]
        );

        $repeater->add_control(
            'social_link',
            [
                'label'       => esc_html__( 'Profile URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://linkedin.com/in/username', 'ultraaddons-elementor-lite' ),
                'default'     => [
                    'url'         => '#',
                    'is_external' => true,
                    'nofollow'    => true,
                ],
            ]
        );

        $repeater->add_control(
            'custom_colors',
            [
                'label'        => esc_html__( 'Custom Colors', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $repeater->add_control(
            'item_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'custom_colors' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'item_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'custom_colors' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'item_hover_icon_color',
            [
                'label'     => esc_html__( 'Hover Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'custom_colors' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $repeater->add_control(
            'item_hover_bg_color',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'custom_colors' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'social_profiles',
            [
                'label'       => esc_html__( 'Social Links', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'social_label' => 'LinkedIn',
                        'social_icon'  => [ 'value' => 'fab fa-linkedin-in', 'library' => 'brands' ],
                    ],
                    [
                        'social_label' => 'Twitter',
                        'social_icon'  => [ 'value' => 'fab fa-twitter', 'library' => 'brands' ],
                    ],
                    [
                        'social_label' => 'GitHub',
                        'social_icon'  => [ 'value' => 'fab fa-github', 'library' => 'brands' ],
                    ],
                ],
                'title_field' => '{{{ social_label }}}',
                'condition'   => [
                    'show_socials' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 1 - Card Container
       ========================================================================= */
    protected function register_container_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_container',
            [
                'label' => esc_html__( 'Card Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_max_width',
            [
                'label'      => esc_html__( 'Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 200, 'max' => 1200 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-item' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => '20',
                    'right'    => '20',
                    'bottom'   => '20',
                    'left'     => '20',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_ua_card_container_style' );

        // Normal State
        $this->start_controls_tab(
            'tab_ua_card_container_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-card-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-card-inner',
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .ua-card-inner',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'tab_ua_card_container_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-card-inner:hover',
            ]
        );

        $this->add_control(
            'card_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-inner:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-card-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'card_hover_translate_y',
            [
                'label'      => esc_html__( 'Hover Lift (Translate Y)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => -20, 'max' => 20 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-inner:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 2 - Media & Badge
       ========================================================================= */
    protected function register_media_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_media',
            [
                'label' => esc_html__( 'Media & Badge', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_media_style',
            [
                'label' => esc_html__( 'Image Style', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'media_width',
            [
                'label'      => esc_html__( 'Image Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 40, 'max' => 800 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-media-wrap' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-card-media-wrap img, {{WRAPPER}} .ua-card-photo' => 'width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_height',
            [
                'label'      => esc_html__( 'Image Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range'      => [
                    'px' => [ 'min' => 40, 'max' => 600 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-media-wrap' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-card-media-wrap img, {{WRAPPER}} .ua-card-photo' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'media_object_fit',
            [
                'label'     => esc_html__( 'Object Fit', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'cover',
                'options'   => [
                    'cover'      => esc_html__( 'Cover (Fill & Crop)', 'ultraaddons-elementor-lite' ),
                    'contain'    => esc_html__( 'Contain (Fit Whole Image)', 'ultraaddons-elementor-lite' ),
                    'fill'       => esc_html__( 'Fill / Stretch', 'ultraaddons-elementor-lite' ),
                    'scale-down' => esc_html__( 'Scale Down', 'ultraaddons-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-card-media-wrap img, {{WRAPPER}} .ua-card-photo' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-media-wrap, {{WRAPPER}} .ua-card-media-wrap img, {{WRAPPER}} .ua-card-photo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'media_border',
                'selector' => '{{WRAPPER}} .ua-card-media-wrap',
            ]
        );

        // Badge Styling
        $this->add_control(
            'heading_badge_style',
            [
                'label'     => esc_html__( 'Floating Badge', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'badge_typography',
                'selector'  => '{{WRAPPER}} .ua-card-badge',
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-badge' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-badge' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 3 - Title & Subtitle
       ========================================================================= */
    protected function register_title_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_title',
            [
                'label' => esc_html__( 'Title & Subtitle', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Subtitle
        $this->add_control(
            'heading_style_subtitle',
            [
                'label' => esc_html__( 'Subtitle / Category', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-card-designation',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-designation' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-designation' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Title
        $this->add_control(
            'heading_style_title',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-card-headline, {{WRAPPER}} .ua-card-headline a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-headline, {{WRAPPER}} .ua-card-headline a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-headline a:hover' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'title_link[url]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-headline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 4 - Description
       ========================================================================= */
    protected function register_desc_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_desc',
            [
                'label' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ua-card-bio',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-bio' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_spacing',
            [
                'label'      => esc_html__( 'Bottom Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 5 - Action Button
       ========================================================================= */
    protected function register_button_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_button',
            [
                'label' => esc_html__( 'Action Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_alignment',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'    => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Full Width', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'      => 'left',
                'prefix_class' => 'ua-card-btn-align%s-',
                'selectors_dictionary' => [
                    'left'    => 'flex-start',
                    'center'  => 'center',
                    'right'   => 'flex-end',
                    'justify' => 'space-between',
                ],
                'selectors'    => [
                    '{{WRAPPER}} .ua-card-action-wrap' => 'display: flex !important; justify-content: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .ua-card-cta-btn',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-cta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_ua_card_button_style' );

        // Normal
        $this->start_controls_tab(
            'tab_ua_card_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-cta-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-card-cta-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_border',
                'selector' => '{{WRAPPER}} .ua-card-cta-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_shadow',
                'selector' => '{{WRAPPER}} .ua-card-cta-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_ua_card_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'btn_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-cta-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_bg_hover',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-card-cta-btn:hover',
            ]
        );

        $this->add_control(
            'btn_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-cta-btn:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-card-cta-btn:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 6 - Price & Wishlist
       ========================================================================= */
    protected function register_price_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_price',
            [
                'label'     => esc_html__( 'Price & Wishlist', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_pricing' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_style_price_regular',
            [
                'label' => esc_html__( 'Regular Price (Strikethrough)', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'regular_price_typography',
                'selector' => '{{WRAPPER}} .ua-card-price-regular',
            ]
        );

        $this->add_control(
            'regular_price_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-price-regular' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_style_price_sale',
            [
                'label'     => esc_html__( 'Sale Price (Accent)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sale_price_typography',
                'selector' => '{{WRAPPER}} .ua-card-price-sale',
            ]
        );

        $this->add_control(
            'sale_price_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-price-sale' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Wishlist Icon Styling
        $this->add_control(
            'heading_style_wishlist',
            [
                'label'     => esc_html__( 'Wishlist Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wishlist_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 12, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-wish-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-wish-btn' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_icon_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-wish-btn:hover' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-wish-btn' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_bg_hover_color',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-wish-btn:hover' => 'background-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wishlist_box_size',
            [
                'label'      => esc_html__( 'Box Size (Width & Height)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 20, 'max' => 80 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-wish-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;',
                ],
                'condition'  => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /* =========================================================================
       STYLE TAB: Section 7 - Social Profiles
       ========================================================================= */
    protected function register_social_style_controls() {
        $this->start_controls_section(
            'section_ua_card_style_socials',
            [
                'label'     => esc_html__( 'Social Profiles', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_socials' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_box_size',
            [
                'label'      => esc_html__( 'Box Size (Width & Height)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 20, 'max' => 80 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-social-link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 40 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-social-link' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_gap',
            [
                'label'      => esc_html__( 'Icons Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 4, 'max' => 40 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-social-strip' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_ua_card_socials_style' );

        // Normal Tab
        $this->start_controls_tab(
            'tab_ua_card_socials_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-social-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-social-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'social_border',
                'selector' => '{{WRAPPER}} .ua-card-social-link',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'tab_ua_card_socials_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'social_color_hover',
            [
                'label'     => esc_html__( 'Hover Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-social-link:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'social_bg_hover',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-social-link:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'social_border_color_hover',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-card-social-link:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'social_shadow_hover',
                'selector' => '{{WRAPPER}} .ua-card-social-link:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'social_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .ua-card-social-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $preset     = ! empty( $settings['preset_style'] ) ? sanitize_html_class( $settings['preset_style'] ) : 'clean-classic';
        $title      = $settings['card_title'] ?? '';
        $title_tag  = ! empty( $settings['title_tag'] ) ? sanitize_key( $settings['title_tag'] ) : 'h3';
        $title_link = ! empty( $settings['title_link']['url'] ) ? $settings['title_link']['url'] : '';

        $subtitle     = $settings['card_subtitle'] ?? '';
        $subtitle_tag = ! empty( $settings['subtitle_tag'] ) ? sanitize_key( $settings['subtitle_tag'] ) : 'span';

        $description = $settings['card_description'] ?? '';

        $button_text = $settings['button_text'] ?? '';
        $button_link = ! empty( $settings['button_link']['url'] ) ? $settings['button_link']['url'] : '';

        $show_badge     = 'yes' === ( $settings['show_badge'] ?? '' );
        $badge_text     = $settings['badge_text'] ?? '';
        $badge_position = ! empty( $settings['badge_position'] ) ? sanitize_html_class( $settings['badge_position'] ) : 'top-right';

        $show_pricing   = 'yes' === ( $settings['show_pricing'] ?? '' );
        $price_regular  = $settings['price_regular'] ?? '';
        $price_sale     = $settings['price_sale'] ?? '';
        $show_wishlist  = 'yes' === ( $settings['show_wishlist'] ?? '' );
        $wishlist_link  = ! empty( $settings['wishlist_link']['url'] ) ? $settings['wishlist_link']['url'] : '#';

        $show_socials   = 'yes' === ( $settings['show_socials'] ?? '' );
        $social_list    = $settings['social_profiles'] ?? [];

        // Wrapper classes
        $wrapper_classes = [
            'ua-card-item',
            'ua-card-preset-' . $preset,
        ];

        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
            <div class="ua-card-inner">

                <?php // Media Section ?>
                <?php if ( ! empty( $settings['card_image']['url'] ) ) : ?>
                    <div class="ua-card-media-wrap">
                        <?php
                        $image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'card_image' );
                        if ( strpos( $image_html, 'class="' ) !== false ) {
                            $image_html = str_replace( 'class="', 'class="ua-card-photo ', $image_html );
                        } else {
                            $image_html = str_replace( '<img ', '<img class="ua-card-photo" ', $image_html );
                        }
                        echo $image_html;
                        ?>

                        <?php if ( $show_badge && ! empty( $badge_text ) ) : ?>
                            <span class="ua-card-badge ua-card-badge-<?php echo esc_attr( $badge_position ); ?>">
                                <?php echo esc_html( $badge_text ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php // Content Body Section ?>
                <div class="ua-card-body">

                    <?php // Subtitle / Designation ?>
                    <?php if ( ! empty( $subtitle ) ) : ?>
                        <<?php echo esc_html( $subtitle_tag ); ?> class="ua-card-designation">
                            <?php echo esc_html( $subtitle ); ?>
                        </<?php echo esc_html( $subtitle_tag ); ?>>
                    <?php endif; ?>

                    <?php // Title ?>
                    <?php if ( ! empty( $title ) ) : ?>
                        <<?php echo esc_html( $title_tag ); ?> class="ua-card-headline">
                            <?php if ( ! empty( $title_link ) ) :
                                $this->add_link_attributes( 'card_title_link', $settings['title_link'] );
                                ?>
                                <a <?php $this->print_render_attribute_string( 'card_title_link' ); ?>>
                                    <?php echo esc_html( $title ); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html( $title ); ?>
                            <?php endif; ?>
                        </<?php echo esc_html( $title_tag ); ?>>
                    <?php endif; ?>

                    <?php // Description ?>
                    <?php if ( ! empty( $description ) ) : ?>
                        <div class="ua-card-bio">
                            <?php echo wp_kses_post( $description ); ?>
                        </div>
                    <?php endif; ?>

                    <?php // Price & Wishlist ?>
                    <?php if ( $show_pricing && ( ! empty( $price_regular ) || ! empty( $price_sale ) ) ) : ?>
                        <div class="ua-card-meta-row">
                            <div class="ua-card-price-wrap">
                                <?php if ( ! empty( $price_regular ) ) : ?>
                                    <span class="ua-card-price-regular"><?php echo esc_html( $price_regular ); ?></span>
                                <?php endif; ?>
                                <?php if ( ! empty( $price_sale ) ) : ?>
                                    <span class="ua-card-price-sale"><?php echo esc_html( $price_sale ); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ( $show_wishlist && ! empty( $settings['wishlist_icon']['value'] ) ) :
                                $this->add_link_attributes( 'wishlist_link_attr', $settings['wishlist_link'] );
                                ?>
                                <a class="ua-card-wish-btn" <?php $this->print_render_attribute_string( 'wishlist_link_attr' ); ?> aria-label="<?php esc_attr_e( 'Add to Wishlist', 'ultraaddons-elementor-lite' ); ?>">
                                    <?php Icons_Manager::render_icon( $settings['wishlist_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php // Social Profiles ?>
                    <?php if ( $show_socials && ! empty( $social_list ) ) : ?>
                        <div class="ua-card-social-strip">
                            <?php foreach ( $social_list as $index => $item ) :
                                $link_key = 'social_link_' . $index;
                                if ( ! empty( $item['social_link']['url'] ) ) {
                                    $this->add_link_attributes( $link_key, $item['social_link'] );
                                }
                                ?>
                                <a class="ua-card-social-link elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>" <?php $this->print_render_attribute_string( $link_key ); ?> aria-label="<?php echo esc_attr( $item['social_label'] ?? 'Social Profile' ); ?>">
                                    <?php Icons_Manager::render_icon( $item['social_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php // Action Button ?>
                    <?php if ( ! empty( $button_text ) ) :
                        $this->add_link_attributes( 'card_btn_link', $settings['button_link'] );
                        $icon_pos = ! empty( $settings['button_icon_position'] ) ? $settings['button_icon_position'] : 'after';
                        ?>
                        <div class="ua-card-action-wrap">
                            <a class="ua-card-cta-btn" <?php $this->print_render_attribute_string( 'card_btn_link' ); ?>>
                                <?php if ( 'before' === $icon_pos && ! empty( $settings['button_icon']['value'] ) ) : ?>
                                    <span class="ua-card-btn-icon-before">
                                        <?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </span>
                                <?php endif; ?>

                                <span class="ua-card-btn-label"><?php echo esc_html( $button_text ); ?></span>

                                <?php if ( 'after' === $icon_pos && ! empty( $settings['button_icon']['value'] ) ) : ?>
                                    <span class="ua-card-btn-icon-after">
                                        <?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                </div><?php // .ua-card-body ?>

            </div><?php // .ua-card-inner ?>
        </div><?php // .ua-card-item ?>
        <?php
    }
}
