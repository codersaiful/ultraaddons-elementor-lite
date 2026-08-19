<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * UltraAddons Team Box / Carousel Widget
 *
 * Combines full featured single Team Member cards and dynamic Team Carousel / Slider
 * with multiple design presets (Simple, Overlay, Centered, Circle, Social on Bottom, Social on Right),
 * customizable typography, social media profiles, and responsive carousel controls.
 *
 * @since 1.1.0.12
 * @package UltraAddons
 */
class Team_Box extends Base {

    /**
     * Constructor: Register dependencies
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_script(
            'ua-slick',
            ULTRA_ADDONS_ASSETS . 'vendor/js/slick.min.js',
            [ 'jquery' ],
            '1.8.1',
            true
        );

        wp_register_style(
            'ultraaddons-team-box',
            ULTRA_ADDONS_ASSETS . 'css/widgets/team-box.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-team-box' );
    }

    public function get_icon() {
        return 'ultraaddons eicon-person';
    }

    public function get_keywords() {
        return [
            'ultraaddons-elementor-lite',
            'ua',
            'team',
            'team member',
            'team box',
            'team carousel',
            'team slider',
            'member',
            'person',
            'card',
            'profile',
            'author',
            'meet the team',
            'our team'
        ];
    }

    public function get_script_depends() {
        return [ 'jquery', 'imagesloaded', 'ua-slick' ];
    }

    public function get_style_depends() {
        return [ 'ultraaddons-widgets-style', 'ultraaddons-team-box' ];
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_layout_controls();
        $this->content_single_image_controls();
        $this->content_single_details_controls();
        $this->content_single_social_controls();
        $this->content_carousel_items_controls();
        $this->content_carousel_settings_controls();

        // Style Tab
        $this->style_content_card_controls();
        $this->style_image_controls();
        $this->style_typography_controls();
        $this->style_social_profiles_controls();
        $this->style_carousel_navigation_controls();
        $this->style_carousel_dots_controls();
    }

    /*==========================================================================
     * CONTENT TAB: Layout & Presets
     *========================================================================*/
    protected function content_layout_controls() {
        $this->start_controls_section(
            'ua_section_team_layout',
            [
                'label' => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'team_layout_mode',
            [
                'label'   => esc_html__( 'Display Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'single',
                'options' => [
                    'single'   => esc_html__( 'Single Member', 'ultraaddons-elementor-lite' ),
                    'carousel' => esc_html__( 'Carousel / Slider', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'team_preset',
            [
                'label'       => esc_html__( 'Preset Style', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'ua-team-members-simple',
                'options'     => [
                    'ua-team-members-simple'        => esc_html__( 'Simple Style', 'ultraaddons-elementor-lite' ),
                    'ua-team-members-overlay'       => esc_html__( 'Overlay Style', 'ultraaddons-elementor-lite' ),
                    'ua-team-members-centered'      => esc_html__( 'Centered Style', 'ultraaddons-elementor-lite' ),
                    'ua-team-members-circle'        => esc_html__( 'Circle Style', 'ultraaddons-elementor-lite' ),
                    'ua-team-members-social-bottom' => esc_html__( 'Social on Bottom', 'ultraaddons-elementor-lite' ),
                    'ua-team-members-social-right'  => esc_html__( 'Social on Right', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB: Single Member Image
     *========================================================================*/
    protected function content_single_image_controls() {
        $this->start_controls_section(
            'ua_section_team_image',
            [
                'label'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'team_layout_mode' => 'single',
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => esc_html__( 'Choose Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'default'   => 'full',
                'condition' => [
                    'image[url]!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB: Single Member Details
     *========================================================================*/
    protected function content_single_details_controls() {
        $this->start_controls_section(
            'ua_section_team_content',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'team_layout_mode' => 'single',
                ],
            ]
        );

        $this->add_control(
            'name_heading',
            [
                'label' => esc_html__( 'Name', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'name',
            [
                'label'   => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => esc_html__( 'John Doe', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'name_tag',
            [
                'label'       => esc_html__( 'HTML Tag', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'h1'   => [ 'title' => 'H1', 'icon' => 'eicon-editor-h1' ],
                    'h2'   => [ 'title' => 'H2', 'icon' => 'eicon-editor-h2' ],
                    'h3'   => [ 'title' => 'H3', 'icon' => 'eicon-editor-h3' ],
                    'h4'   => [ 'title' => 'H4', 'icon' => 'eicon-editor-h4' ],
                    'h5'   => [ 'title' => 'H5', 'icon' => 'eicon-editor-h5' ],
                    'h6'   => [ 'title' => 'H6', 'icon' => 'eicon-editor-h6' ],
                    'div'  => [ 'title' => 'div', 'text' => 'div' ],
                    'span' => [ 'title' => 'span', 'text' => 'span' ],
                    'p'    => [ 'title' => 'p', 'text' => 'p' ],
                ],
                'default'   => 'h2',
                'toggle'    => false,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'job_position_heading',
            [
                'label'     => esc_html__( 'Job Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'job_title',
            [
                'label'   => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => esc_html__( 'Software Engineer', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'job_title_tag',
            [
                'label'       => esc_html__( 'HTML Tag', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'h1'   => [ 'title' => 'H1', 'icon' => 'eicon-editor-h1' ],
                    'h2'   => [ 'title' => 'H2', 'icon' => 'eicon-editor-h2' ],
                    'h3'   => [ 'title' => 'H3', 'icon' => 'eicon-editor-h3' ],
                    'h4'   => [ 'title' => 'H4', 'icon' => 'eicon-editor-h4' ],
                    'h5'   => [ 'title' => 'H5', 'icon' => 'eicon-editor-h5' ],
                    'h6'   => [ 'title' => 'H6', 'icon' => 'eicon-editor-h6' ],
                    'div'  => [ 'title' => 'div', 'text' => 'div' ],
                    'span' => [ 'title' => 'span', 'text' => 'span' ],
                    'p'    => [ 'title' => 'p', 'text' => 'p' ],
                ],
                'default'   => 'h3',
                'toggle'    => false,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'description',
            [
                'label'   => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => [ 'active' => true ],
                'default' => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'member_link',
            [
                'label'       => esc_html__( 'Profile Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB: Single Member Social Profiles
     *========================================================================*/
    protected function content_single_social_controls() {
        $this->start_controls_section(
            'ua_section_team_social_profiles',
            [
                'label'     => esc_html__( 'Social Profiles', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'team_layout_mode' => 'single',
                ],
            ]
        );

        $this->add_control(
            'enable_social_profiles',
            [
                'label'   => esc_html__( 'Display Social Profiles?', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_new',
            [
                'label'   => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'default'     => [
                    'url'         => 'https://facebook.com',
                    'is_external' => true,
                ],
                'placeholder' => esc_html__( 'Place URL here', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'social_profile_links',
            [
                'type'        => Controls_Manager::REPEATER,
                'condition'   => [
                    'enable_social_profiles!' => '',
                ],
                'default'     => [
                    [
                        'social_new' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                        'link'       => [ 'url' => 'https://facebook.com' ],
                    ],
                    [
                        'social_new' => [ 'value' => 'fab fa-x-twitter', 'library' => 'fa-brands' ],
                        'link'       => [ 'url' => 'https://x.com' ],
                    ],
                    [
                        'social_new' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                        'link'       => [ 'url' => 'https://linkedin.com' ],
                    ],
                    [
                        'social_new' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ],
                        'link'       => [ 'url' => 'https://instagram.com' ],
                    ],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '<i class="{{ social_new.value }}"></i>',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB: Carousel Items (Repeater)
     *========================================================================*/
    protected function content_carousel_items_controls() {
        $this->start_controls_section(
            'ua_section_carousel_items',
            [
                'label'     => esc_html__( 'Team Members', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'team_layout_mode' => 'carousel',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'member_image',
            [
                'label'   => esc_html__( 'Member Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'member_name',
            [
                'label'   => esc_html__( 'Name', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => esc_html__( 'John Doe', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'member_job',
            [
                'label'   => esc_html__( 'Job / Designation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => esc_html__( 'Software Engineer', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'member_bio',
            [
                'label'   => esc_html__( 'Description / Bio', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => [ 'active' => true ],
                'default' => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'member_link',
            [
                'label'       => esc_html__( 'Profile URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
            ]
        );

        // Social Icons for Repeater Item
        for ( $i = 1; $i <= 4; $i++ ) {
            $repeater->add_control(
                "social_icon_{$i}",
                [
                    'label'     => sprintf( esc_html__( 'Social Icon %d', 'ultraaddons-elementor-lite' ), $i ),
                    'type'      => Controls_Manager::ICONS,
                    'separator' => 'before',
                ]
            );

            $repeater->add_control(
                "social_url_{$i}",
                [
                    'label'       => sprintf( esc_html__( 'Social Link %d', 'ultraaddons-elementor-lite' ), $i ),
                    'type'        => Controls_Manager::URL,
                    'dynamic'     => [ 'active' => true ],
                    'placeholder' => esc_html__( 'https://profile-url.com', 'ultraaddons-elementor-lite' ),
                    'condition'   => [
                        "social_icon_{$i}[value]!" => '',
                    ],
                ]
            );
        }

        $this->add_control(
            'carousel_members',
            [
                'label'       => esc_html__( 'Members List', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ member_name }}} - {{{ member_job }}}',
                'default'     => [
                    [
                        'member_image'  => [ 'url' => Utils::get_placeholder_image_src() ],
                        'member_name'   => 'Alex Morgan',
                        'member_job'    => 'Creative Director',
                        'member_bio'    => 'Experienced in UX architecture, brand design and creative team leadership.',
                        'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                        'social_url_1'  => [ 'url' => 'https://facebook.com' ],
                        'social_icon_2' => [ 'value' => 'fab fa-x-twitter', 'library' => 'fa-brands' ],
                        'social_url_2'  => [ 'url' => 'https://x.com' ],
                        'social_icon_3' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                        'social_url_3'  => [ 'url' => 'https://linkedin.com' ],
                    ],
                    [
                        'member_image'  => [ 'url' => Utils::get_placeholder_image_src() ],
                        'member_name'   => 'Sarah Jenkins',
                        'member_job'    => 'Lead Developer',
                        'member_bio'    => 'Specializes in modern front-end frameworks, high-performance web apps, and API architecture.',
                        'social_icon_1' => [ 'value' => 'fab fa-github', 'library' => 'fa-brands' ],
                        'social_url_1'  => [ 'url' => 'https://github.com' ],
                        'social_icon_2' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                        'social_url_2'  => [ 'url' => 'https://linkedin.com' ],
                        'social_icon_3' => [ 'value' => 'fab fa-x-twitter', 'library' => 'fa-brands' ],
                        'social_url_3'  => [ 'url' => 'https://x.com' ],
                    ],
                    [
                        'member_image'  => [ 'url' => Utils::get_placeholder_image_src() ],
                        'member_name'   => 'David Miller',
                        'member_job'    => 'Marketing Strategist',
                        'member_bio'    => 'Expert in growth marketing, conversion rate optimization and performance branding.',
                        'social_icon_1' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                        'social_url_1'  => [ 'url' => 'https://linkedin.com' ],
                        'social_icon_2' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ],
                        'social_url_2'  => [ 'url' => 'https://instagram.com' ],
                        'social_icon_3' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                        'social_url_3'  => [ 'url' => 'https://facebook.com' ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'carousel_thumbnail',
                'default' => 'full',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * CONTENT TAB: Carousel Settings
     *========================================================================*/
    protected function content_carousel_settings_controls() {
        $this->start_controls_section(
            'ua_section_carousel_settings',
            [
                'label'     => esc_html__( 'Carousel Settings', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'team_layout_mode' => 'carousel',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_columns',
            [
                'label'          => esc_html__( 'Columns / Slides to Show', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '1',
                'tablet_default' => '1',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
            ]
        );

        $this->add_control(
            'carousel_slides_to_scroll',
            [
                'label'   => esc_html__( 'Slides to Scroll', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_control(
            'carousel_effect',
            [
                'label'   => esc_html__( 'Effect', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
                    'fade'  => esc_html__( 'Fade (Single Column)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'carousel_autoplay',
            [
                'label'        => esc_html__( 'Autoplay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'carousel_autoplay_speed',
            [
                'label'     => esc_html__( 'Autoplay Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 4000,
                'step'      => 500,
                'condition' => [
                    'carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'carousel_pause_on_hover',
            [
                'label'        => esc_html__( 'Pause on Hover', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [
                    'carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'carousel_loop',
            [
                'label'        => esc_html__( 'Infinite Loop', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'carousel_speed',
            [
                'label'   => esc_html__( 'Animation Speed (ms)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 700,
                'step'    => 100,
            ]
        );

        $this->add_control(
            'carousel_nav',
            [
                'label'        => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'carousel_nav_icon',
            [
                'label'     => esc_html__( 'Arrow Icon Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fas fa-angle-left',
                'options'   => [
                    'fas fa-angle-left'          => esc_html__( 'Angle', 'ultraaddons-elementor-lite' ),
                    'fas fa-chevron-left'        => esc_html__( 'Chevron', 'ultraaddons-elementor-lite' ),
                    'fas fa-arrow-left'          => esc_html__( 'Arrow', 'ultraaddons-elementor-lite' ),
                    'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'ultraaddons-elementor-lite' ),
                    'fas fa-caret-left'          => esc_html__( 'Caret', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'carousel_nav' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'carousel_dots',
            [
                'label'        => esc_html__( 'Dots Pagination', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB: Content Card
     *========================================================================*/
    protected function style_content_card_controls() {
        $this->start_controls_section(
            'ua_section_style_content_card',
            [
                'label' => esc_html__( 'Content Card', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_card_height',
            [
                'label'      => esc_html__( 'Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 600 ],
                    'em' => [ 'min' => 0, 'max' => 40 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item .ua-team-content' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'enable_text_overlay',
            [
                'label'        => esc_html__( 'Description Overlay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
                'condition'    => [
                    'team_preset' => 'ua-team-members-simple',
                ],
            ]
        );

        $this->add_control(
            'overlay_background',
            [
                'label'     => esc_html__( 'Overlay Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255,255,255,0.85)',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-members-overlay .ua-team-content'         => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-image .ua-team-text.ua-team-text-overlay' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'team_preset',
                            'operator' => '=',
                            'value'    => 'ua-team-members-overlay',
                        ],
                        [
                            'name'     => 'enable_text_overlay',
                            'operator' => '=',
                            'value'    => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-team-item .ua-team-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_alignment',
            [
                'label'        => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => true,
                'options'      => [
                    'left'     => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'    => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'      => 'center',
                'prefix_class' => 'ua-team-align%s-',
                'selectors'    => [
                    '{{WRAPPER}} .ua-team-item'                     => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-content'     => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-member-name' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-member-position' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-text'        => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-content p'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-member-social-profiles'    => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-social-bottom-bar'         => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item .ua-team-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-team-item',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-team-item',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB: Image
     *========================================================================*/
    protected function style_image_controls() {
        $this->start_controls_section(
            'ua_section_style_image',
            [
                'label' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'range'      => [
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                    'px' => [ 'min' => 0, 'max' => 1000 ],
                ],
                'size_units' => [ '%', 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item figure img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'team_preset!' => 'ua-team-members-circle',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item figure img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-team-item figure img',
            ]
        );

        $this->add_control(
            'image_rounded',
            [
                'label'        => esc_html__( 'Rounded Avatar?', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'ua-team-avatar-rounded',
                'default'      => '',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'image_rounded!' => 'ua-team-avatar-rounded',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-team-item figure img',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB: Color & Typography
     *========================================================================*/
    protected function style_typography_controls() {
        $this->start_controls_section(
            'ua_section_style_typography',
            [
                'label' => esc_html__( 'Color &amp; Typography', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Name
        $this->add_control(
            'name_style_heading',
            [
                'label' => esc_html__( 'Member Name', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-item .ua-team-member-name'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-member-name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .ua-team-item .ua-team-member-name',
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item .ua-team-member-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Job Position
        $this->add_control(
            'job_position_style_heading',
            [
                'label'     => esc_html__( 'Job Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'job_position_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7a7a7a',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-item .ua-team-member-position' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_position_typography',
                'selector' => '{{WRAPPER}} .ua-team-item .ua-team-member-position',
            ]
        );

        $this->add_responsive_control(
            'job_position_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item .ua-team-member-position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Description
        $this->add_control(
            'description_style_heading',
            [
                'label'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-item .ua-team-content .ua-team-text'                     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-item .ua-team-image .ua-team-text.ua-team-text-overlay' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .ua-team-item .ua-team-content .ua-team-text, {{WRAPPER}} .ua-team-item .ua-team-image .ua-team-text.ua-team-text-overlay',
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-item .ua-team-content .ua-team-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB: Social Profiles
     *========================================================================*/
    protected function style_social_profiles_controls() {
        $this->start_controls_section(
            'ua_section_style_social_profiles',
            [
                'label' => esc_html__( 'Social Profiles', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'social_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 6, 'max' => 120 ],
                ],
                'default'    => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-member-social-link > a'     => 'width: calc({{SIZE}}{{UNIT}} + 18px); height: calc({{SIZE}}{{UNIT}} + 18px); line-height: calc({{SIZE}}{{UNIT}} + 18px); font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-team-member-social-link > a i'   => 'font-size: {{SIZE}}{{UNIT}}; line-height: 1;',
                    '{{WRAPPER}} .ua-team-member-social-link > a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-team-member-social-link > a img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_profiles_align',
            [
                'label'       => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'     => [
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
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
                'selectors'   => [
                    '{{WRAPPER}} .ua-team-member-social-profiles'                           => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-social-bottom-bar'                                => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-social-bottom-bar .ua-team-member-social-profiles' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_profiles_margin',
            [
                'label'      => esc_html__( 'Section Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-content > .ua-team-member-social-profiles' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-team-image > .ua-team-member-social-profiles'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-team-social-bottom-bar'                         => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_padding',
            [
                'label'      => esc_html__( 'Icon Box Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-member-social-link > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_icons_spacing',
            [
                'label'      => esc_html__( 'Icon Distance', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-member-social-profiles li.ua-team-member-social-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_used_gradient_bg',
            [
                'label'        => esc_html__( 'Use Gradient Background', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_controls_tabs( 'ua_team_social_icons_style_tabs' );

        // Normal Tab
        $this->start_controls_tab(
            'ua_tab_social_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'social_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-member-social-link > a'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-member-social-link > a svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_background',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-member-social-link > a' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'social_icons_used_gradient_bg' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'social_icon_gradient_background',
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => '{{WRAPPER}} .ua-team-member-social-link > a',
                'condition' => [
                    'social_icons_used_gradient_bg' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'social_icon_border',
                'selector' => '{{WRAPPER}} .ua-team-member-social-link > a',
            ]
        );

        $this->add_responsive_control(
            'social_icon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [ 'max' => 100 ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-member-social-link > a' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'ua_tab_social_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'social_icon_hover_color',
            [
                'label'     => esc_html__( 'Icon Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-member-social-link > a:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-team-member-social-link > a:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_icon_hover_background',
            [
                'label'     => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#015286',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-member-social-link > a:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'social_icons_used_gradient_bg' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'social_icon_hover_gradient_background',
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => '{{WRAPPER}} .ua-team-member-social-link > a:hover',
                'condition' => [
                    'social_icons_used_gradient_bg' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'social_icon_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-team-member-social-link > a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB: Carousel Navigation
     *========================================================================*/
    protected function style_carousel_navigation_controls() {
        $this->start_controls_section(
            'ua_section_style_carousel_nav',
            [
                'label'     => esc_html__( 'Carousel Navigation', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'team_layout_mode' => 'carousel',
                    'carousel_nav'     => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_nav_size',
            [
                'label'      => esc_html__( 'Arrow Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 50 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-carousel-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_nav_box_size',
            [
                'label'      => esc_html__( 'Box Dimensions', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 20, 'max' => 80 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-carousel-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_carousel_nav_style_tabs' );

        // Normal
        $this->start_controls_tab(
            'ua_carousel_nav_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'carousel_nav_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-carousel-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_nav_bg',
            [
                'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-carousel-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'carousel_nav_border',
                'selector' => '{{WRAPPER}} .ua-team-carousel-arrow',
            ]
        );

        $this->add_control(
            'carousel_nav_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-carousel-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'carousel_nav_shadow',
                'selector' => '{{WRAPPER}} .ua-team-carousel-arrow',
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'ua_carousel_nav_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'carousel_nav_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-carousel-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_nav_hover_bg',
            [
                'label'     => esc_html__( 'Hover Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-carousel-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_nav_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-team-carousel-arrow:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB: Carousel Dots
     *========================================================================*/
    protected function style_carousel_dots_controls() {
        $this->start_controls_section(
            'ua_section_style_carousel_dots',
            [
                'label'     => esc_html__( 'Carousel Dots', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'team_layout_mode' => 'carousel',
                    'carousel_dots'    => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_dots_size',
            [
                'label'      => esc_html__( 'Dot Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 4, 'max' => 25 ],
                ],
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-dots .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-team-dots .slick-dots li'        => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_dots_color',
            [
                'label'     => esc_html__( 'Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-dots .slick-dots li button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'carousel_dots_active_color',
            [
                'label'     => esc_html__( 'Active Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '{{WRAPPER}} .ua-team-dots .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_dots_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-team-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER HELPER METHODS
     *========================================================================*/

    /**
     * Validate HTML Tag
     */
    protected function validate_html_tag( $tag ) {
        $allowed_tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
        return in_array( strtolower( $tag ), $allowed_tags, true ) ? $tag : 'div';
    }

    /**
     * Render Single Member Card
     */
    protected function render_single_team_member( $settings ) {
        $image_url = '';
        $alt_text  = $settings['name'] ?? '';

        if ( ! empty( $settings['image']['id'] ) ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings );
            $alt_text  = get_post_meta( $settings['image']['id'], '_wp_attachment_image_alt', true );
        } elseif ( ! empty( $settings['image']['url'] ) ) {
            $image_url = $settings['image']['url'];
        }

        $preset_class  = $settings['team_preset'] ?? 'ua-team-members-simple';
        $rounded_class = $settings['image_rounded'] ?? '';
        $item_classes  = trim( "ua-team-item {$preset_class} {$rounded_class}" );

        $is_text_overlay = ( isset( $settings['enable_text_overlay'] ) && 'yes' === $settings['enable_text_overlay'] && 'ua-team-members-simple' === $preset_class );
        $text_class      = $is_text_overlay ? 'ua-team-text ua-team-text-overlay' : 'ua-team-text';

        $title_tag    = $this->validate_html_tag( $settings['name_tag'] ?? 'h2' );
        $position_tag = $this->validate_html_tag( $settings['job_title_tag'] ?? 'h3' );

        $has_link = ! empty( $settings['member_link']['url'] );
        if ( $has_link ) {
            $this->add_link_attributes( 'member_profile_link', $settings['member_link'] );
        }
        ?>
        <div class="<?php echo esc_attr( $item_classes ); ?>">
            <div class="ua-team-item-inner">
                
                <div class="ua-team-image">
                    <figure>
                        <?php if ( ! empty( $image_url ) ) : ?>
                            <?php if ( $has_link ) : ?>
                                <a <?php echo $this->get_render_attribute_string( 'member_profile_link' ); ?>>
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </figure>

                    <?php if ( 'ua-team-members-social-right' === $preset_class ) : ?>
                        <?php $this->render_social_profiles( $settings ); ?>
                    <?php endif; ?>

                    <?php if ( $is_text_overlay && ! empty( $settings['description'] ) ) : ?>
                        <p class="<?php echo esc_attr( $text_class ); ?>"><?php echo wp_kses_post( $settings['description'] ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="ua-team-content">
                    <?php
                    // Member Name
                    if ( ! empty( $settings['name'] ) ) {
                        if ( $has_link ) {
                            printf(
                                '<%1$s class="ua-team-member-name"><a %2$s>%3$s</a></%1$s>',
                                esc_attr( $title_tag ),
                                $this->get_render_attribute_string( 'member_profile_link' ),
                                wp_kses_post( $settings['name'] )
                            );
                        } else {
                            printf(
                                '<%1$s class="ua-team-member-name">%2$s</%1$s>',
                                esc_attr( $title_tag ),
                                wp_kses_post( $settings['name'] )
                            );
                        }
                    }

                    // Job Position
                    if ( ! empty( $settings['job_title'] ) ) {
                        printf(
                            '<%1$s class="ua-team-member-position">%2$s</%1$s>',
                            esc_attr( $position_tag ),
                            wp_kses_post( $settings['job_title'] )
                        );
                    }
                    ?>

                    <?php if ( 'ua-team-members-social-right' !== $preset_class && 'ua-team-members-social-bottom' !== $preset_class ) : ?>
                        <?php $this->render_social_profiles( $settings ); ?>
                    <?php endif; ?>

                    <?php if ( ! $is_text_overlay && ! empty( $settings['description'] ) ) : ?>
                        <p class="<?php echo esc_attr( $text_class ); ?>"><?php echo wp_kses_post( $settings['description'] ); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ( 'ua-team-members-social-bottom' === $preset_class ) : ?>
                    <div class="ua-team-social-bottom-bar">
                        <?php $this->render_social_profiles( $settings ); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }

    /**
     * Render Social Profiles for Single Member
     */
    protected function render_social_profiles( $settings ) {
        if ( empty( $settings['enable_social_profiles'] ) || empty( $settings['social_profile_links'] ) ) {
            return;
        }
        ?>
        <ul class="ua-team-member-social-profiles">
            <?php foreach ( $settings['social_profile_links'] as $index => $item ) : ?>
                <?php
                if ( empty( $item['social_new']['value'] ) ) {
                    continue;
                }
                $link_key = 'social_link_' . $index;
                $this->add_link_attributes( $link_key, $item['link'] );
                ?>
                <li class="ua-team-member-social-link elementor-repeater-item-<?php echo esc_attr( $item['_id'] ?? $index ); ?>">
                    <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                        <?php
                        if ( isset( $item['social_new']['value']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $item['social_new']['value']['url'] ); ?>" alt="<?php esc_attr_e( 'Social Icon', 'ultraaddons-elementor-lite' ); ?>" />
                        <?php else :
                            Icons_Manager::render_icon( $item['social_new'], [ 'aria-hidden' => 'true' ] );
                        endif;
                        ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

    /**
     * Render Carousel Member Item
     */
    protected function render_carousel_member_item( $member, $index, $settings ) {
        $image_url = '';
        $alt_text  = $member['member_name'] ?? '';

        if ( ! empty( $member['member_image']['id'] ) ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $member['member_image']['id'], 'carousel_thumbnail', $settings );
            $alt_text  = get_post_meta( $member['member_image']['id'], '_wp_attachment_image_alt', true );
        } elseif ( ! empty( $member['member_image']['url'] ) ) {
            $image_url = $member['member_image']['url'];
        } elseif ( ! empty( $settings['image']['id'] ) ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings );
            $alt_text  = get_post_meta( $settings['image']['id'], '_wp_attachment_image_alt', true );
        } elseif ( ! empty( $settings['image']['url'] ) ) {
            $image_url = $settings['image']['url'];
        } else {
            $image_url = Utils::get_placeholder_image_src();
        }

        $preset_class  = $settings['team_preset'] ?? 'ua-team-members-simple';
        $rounded_class = $settings['image_rounded'] ?? '';
        $item_classes  = trim( "ua-team-item {$preset_class} {$rounded_class}" );

        $is_text_overlay = ( isset( $settings['enable_text_overlay'] ) && 'yes' === $settings['enable_text_overlay'] && 'ua-team-members-simple' === $preset_class );
        $text_class      = $is_text_overlay ? 'ua-team-text ua-team-text-overlay' : 'ua-team-text';

        $has_link = ! empty( $member['member_link']['url'] );
        $link_key = 'carousel_member_link_' . $index;
        if ( $has_link ) {
            $this->add_link_attributes( $link_key, $member['member_link'] );
        }
        ?>
        <div class="<?php echo esc_attr( $item_classes ); ?> elementor-repeater-item-<?php echo esc_attr( $member['_id'] ?? $index ); ?>">
            <div class="ua-team-item-inner">
                
                <div class="ua-team-image">
                    <figure>
                        <?php if ( ! empty( $image_url ) ) : ?>
                            <?php if ( $has_link ) : ?>
                                <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </figure>

                    <?php if ( 'ua-team-members-social-right' === $preset_class ) : ?>
                        <?php $this->render_carousel_item_socials( $member, $index ); ?>
                    <?php endif; ?>

                    <?php if ( $is_text_overlay && ! empty( $member['member_bio'] ) ) : ?>
                        <p class="<?php echo esc_attr( $text_class ); ?>"><?php echo wp_kses_post( $member['member_bio'] ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="ua-team-content">
                    <?php if ( ! empty( $member['member_name'] ) ) : ?>
                        <h2 class="ua-team-member-name">
                            <?php if ( $has_link ) : ?>
                                <a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php echo wp_kses_post( $member['member_name'] ); ?></a>
                            <?php else : ?>
                                <?php echo wp_kses_post( $member['member_name'] ); ?>
                            <?php endif; ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ( ! empty( $member['member_job'] ) ) : ?>
                        <h3 class="ua-team-member-position"><?php echo wp_kses_post( $member['member_job'] ); ?></h3>
                    <?php endif; ?>

                    <?php if ( 'ua-team-members-social-right' !== $preset_class && 'ua-team-members-social-bottom' !== $preset_class ) : ?>
                        <?php $this->render_carousel_item_socials( $member, $index ); ?>
                    <?php endif; ?>

                    <?php if ( ! $is_text_overlay && ! empty( $member['member_bio'] ) ) : ?>
                        <p class="<?php echo esc_attr( $text_class ); ?>"><?php echo wp_kses_post( $member['member_bio'] ); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ( 'ua-team-members-social-bottom' === $preset_class ) : ?>
                    <div class="ua-team-social-bottom-bar">
                        <?php $this->render_carousel_item_socials( $member, $index ); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }

    /**
     * Render Social Links for Carousel Item
     */
    protected function render_carousel_item_socials( $member, $item_index ) {
        $has_social = false;
        for ( $s = 1; $s <= 4; $s++ ) {
            if ( ! empty( $member["social_icon_{$s}"]['value'] ) ) {
                $has_social = true;
                break;
            }
        }

        if ( ! $has_social ) {
            return;
        }
        ?>
        <ul class="ua-team-member-social-profiles">
            <?php
            for ( $s = 1; $s <= 4; $s++ ) {
                if ( empty( $member["social_icon_{$s}"]['value'] ) ) {
                    continue;
                }

                $url_data = ! empty( $member["social_url_{$s}"] ) ? $member["social_url_{$s}"] : [ 'url' => '#' ];
                $link_key = "carousel_{$item_index}_social_{$s}";
                $this->add_link_attributes( $link_key, $url_data );
                ?>
                <li class="ua-team-member-social-link">
                    <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                        <?php Icons_Manager::render_icon( $member["social_icon_{$s}"], [ 'aria-hidden' => 'true' ] ); ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <?php
    }

    /**
     * Render Output on Frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $mode     = $settings['team_layout_mode'] ?? 'single';

        if ( 'single' === $mode ) {
            ?>
            <div id="ua-team-member-<?php echo esc_attr( $this->get_id() ); ?>" class="ua-team-member-wrapper">
                <?php $this->render_single_team_member( $settings ); ?>
            </div>
            <?php
            return;
        }

        // Carousel Mode
        if ( empty( $settings['carousel_members'] ) ) {
            return;
        }

        $is_rtl           = is_rtl();
        $direction        = $is_rtl ? 'rtl' : 'ltr';
        $columns          = ! empty( $settings['carousel_columns'] ) ? intval( $settings['carousel_columns'] ) : 1;
        $columns_tablet   = ! empty( $settings['carousel_columns_tablet'] ) ? intval( $settings['carousel_columns_tablet'] ) : ( $columns > 1 ? $columns : 1 );
        $columns_mobile   = ! empty( $settings['carousel_columns_mobile'] ) ? intval( $settings['carousel_columns_mobile'] ) : 1;
        $slides_to_scroll = ! empty( $settings['carousel_slides_to_scroll'] ) ? intval( $settings['carousel_slides_to_scroll'] ) : 1;
        $speed            = ! empty( $settings['carousel_speed'] ) ? intval( $settings['carousel_speed'] ) : 700;
        $autoplay_speed   = ! empty( $settings['carousel_autoplay_speed'] ) ? intval( $settings['carousel_autoplay_speed'] ) : 4000;

        $slick_options = [
            'rtl'            => $is_rtl,
            'infinite'       => ( 'yes' === ( $settings['carousel_loop'] ?? 'yes' ) ),
            'speed'          => $speed,
            'arrows'         => ( 'yes' === ( $settings['carousel_nav'] ?? 'yes' ) ),
            'dots'           => ( 'yes' === ( $settings['carousel_dots'] ?? 'yes' ) ),
            'autoplay'       => ( 'yes' === ( $settings['carousel_autoplay'] ?? 'yes' ) ),
            'autoplaySpeed'  => $autoplay_speed,
            'pauseOnHover'   => ( 'yes' === ( $settings['carousel_pause_on_hover'] ?? 'yes' ) ),
            'prevArrow'      => '#ua-team-prev-' . esc_attr( $this->get_id() ),
            'nextArrow'      => '#ua-team-next-' . esc_attr( $this->get_id() ),
            'slidesToShow'   => $columns,
            'slidesToScroll' => $slides_to_scroll,
            'columnsTablet'  => $columns_tablet,
            'columnsMobile'  => $columns_mobile,
        ];

        $this->add_render_attribute( 'team-carousel-attribute', [
            'class'             => 'ua-team-carousel',
            'dir'               => esc_attr( $direction ),
            'data-slick'        => wp_json_encode( $slick_options ),
            'data-slide-effect' => esc_attr( $settings['carousel_effect'] ?? 'slide' ),
        ] );

        $nav_icon  = ! empty( $settings['carousel_nav_icon'] ) ? $settings['carousel_nav_icon'] : 'fas fa-angle-left';
        $prev_icon = $nav_icon;
        $next_icon = str_replace( 'left', 'right', $nav_icon );

        if ( $is_rtl ) {
            $temp_icon = $prev_icon;
            $prev_icon = $next_icon;
            $next_icon = $temp_icon;
        }
        ?>
        <div id="ua-team-carousel-<?php echo esc_attr( $this->get_id() ); ?>" class="ua-team-carousel-wrap">
            <div <?php echo $this->get_render_attribute_string( 'team-carousel-attribute' ); ?>>
                <?php foreach ( $settings['carousel_members'] as $index => $member ) : ?>
                    <div class="ua-team-carousel-slide">
                        <?php $this->render_carousel_member_item( $member, $index, $settings ); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ( 'yes' === ( $settings['carousel_dots'] ?? 'yes' ) ) : ?>
                <div class="ua-team-dots-container">
                    <div class="ua-team-dots"></div>
                </div>
            <?php endif; ?>

            <?php if ( 'yes' === ( $settings['carousel_nav'] ?? 'yes' ) ) : ?>
                <div class="ua-team-arrow-container">
                    <div class="ua-team-prev-arrow ua-team-carousel-arrow" id="ua-team-prev-<?php echo esc_attr( $this->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="<?php echo esc_attr( $prev_icon ); ?>" aria-hidden="true"></i>
                    </div>
                    <div class="ua-team-next-arrow ua-team-carousel-arrow" id="ua-team-next-<?php echo esc_attr( $this->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Next', 'ultraaddons-elementor-lite' ); ?>">
                        <i class="<?php echo esc_attr( $next_icon ); ?>" aria-hidden="true"></i>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
