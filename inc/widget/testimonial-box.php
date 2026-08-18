<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * UltraAddons Testimonial Box / Carousel Widget
 *
 * A feature-rich Testimonial Box and Carousel widget supporting
 * multi-column layouts, custom speech bubbles, ratings, author avatars,
 * company logos, social media links, navigation arrows, and pagination.
 *
 * @since 1.1.0
 */
class Testimonial_Box extends Base {

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
            'ua-testimonial-box',
            ULTRA_ADDONS_ASSETS . 'css/widgets/testimonial-box.css',
            [],
            ULTRA_ADDONS_VERSION
        );
    }

    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'ultraaddons', 'testimonial', 'testimonial box', 'carousel', 'slider', 'reviews', 'rating', 'stars', 'quote', 'feedback' ];
    }

    public function get_script_depends() {
        return [ 'jquery', 'imagesloaded', 'ua-slick' ];
    }

    public function get_style_depends() {
        return [ 'ultraaddons-widgets-style', 'ua-testimonial-box' ];
    }

    /*==========================================================================
     * CONTROLS REGISTRATION
     *========================================================================*/
    protected function register_controls() {
        $this->content_items_controls();
        $this->content_settings_controls();
        
        $this->style_general_controls();
        $this->style_content_controls();
        $this->style_meta_controls();
        $this->style_social_controls();
        $this->style_nav_controls();
        $this->style_dots_controls();
    }

    protected function _register_controls() {
        $this->register_controls();
    }

    /*--------------------------------------------------------------------------
     * CONTENT TAB: Items (Repeater)
     *--------------------------------------------------------------------------*/
    protected function content_items_controls() {
        $this->start_controls_section(
            'section_testimonial_items',
            [
                'label' => esc_html__( 'Testimonials', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'testimonial_author',
            [
                'label'   => esc_html__( 'Author Name', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => 'John Doe',
            ]
        );

        $repeater->add_control(
            'testimonial_job',
            [
                'label'   => esc_html__( 'Job / Designation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => 'Sony CEO',
            ]
        );

        $repeater->add_control(
            'testimonial_image',
            [
                'label'   => esc_html__( 'Author Avatar', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'testimonial_logo_image',
            [
                'label'   => esc_html__( 'Company Logo', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'testimonial_logo_url',
            [
                'label'       => esc_html__( 'Logo URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'testimonial_logo_image[url]!' => '',
                ],
            ]
        );

        $repeater->add_control(
            'testimonial_title_divider',
            [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $repeater->add_control(
            'testimonial_title',
            [
                'label'   => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => 'Awesome Product',
            ]
        );

        $repeater->add_control(
            'testimonial_rating_amount',
            [
                'label'   => esc_html__( 'Rating Score', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 0.1,
                'default' => 5,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'testimonial_content',
            [
                'label'   => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'testimonial_date',
            [
                'label'   => esc_html__( 'Date / Time', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
                'default' => '2 Days Ago',
            ]
        );

        $repeater->add_control(
            'social_media_divider',
            [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        // Social Media Icons 1-5
        for ( $s = 1; $s <= 5; $s++ ) {
            $repeater->add_control(
                "social_icon_{$s}",
                [
                    'label'       => sprintf( esc_html__( 'Social Icon %d', 'ultraaddons-elementor-lite' ), $s ),
                    'type'        => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'default'     => ( $s === 1 ) ? [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ] :
                                     ( ( $s === 2 ) ? [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ] :
                                     ( ( $s === 3 ) ? [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ] : [] ) ),
                ]
            );

            $repeater->add_control(
                "social_url_{$s}",
                [
                    'label'       => sprintf( esc_html__( 'Social URL %d', 'ultraaddons-elementor-lite' ), $s ),
                    'type'        => Controls_Manager::URL,
                    'placeholder' => esc_html__( 'https://your-social-link.com', 'ultraaddons-elementor-lite' ),
                    'condition'   => [
                        "social_icon_{$s}[value]!" => '',
                    ],
                ]
            );
        }

        $this->add_control(
            'testimonial_items',
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'testimonial_author'        => 'John Doe',
                        'testimonial_job'           => 'CEO, Envato',
                        'testimonial_title'         => 'Outstanding Experience',
                        'testimonial_rating_amount' => 5,
                        'testimonial_content'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet.',
                        'testimonial_date'          => '2 Days Ago',
                        'social_icon_1'             => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                        'social_icon_2'             => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
                    ],
                    [
                        'testimonial_author'        => 'Sarah Connor',
                        'testimonial_job'           => 'Lead Designer, Google',
                        'testimonial_title'         => 'Highly Recommended!',
                        'testimonial_rating_amount' => 5,
                        'testimonial_content'       => 'Maecenas lobortis ligula vel tellus sagittis ullamcorper vestibulum pellentesque. Aliquam porttitor tellus enim porta ut.',
                        'testimonial_date'          => '1 Week Ago',
                        'social_icon_1'             => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
                        'social_icon_2'             => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                    ],
                    [
                        'testimonial_author'        => 'Alex Mercer',
                        'testimonial_job'           => 'Product Manager, Stripe',
                        'testimonial_title'         => 'Clean & Fast Service',
                        'testimonial_rating_amount' => 4.5,
                        'testimonial_content'       => 'Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Suspendisse potenti elementum dignissim.',
                        'testimonial_date'          => '3 Weeks Ago',
                        'social_icon_1'             => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                        'social_icon_2'             => [ 'value' => 'fab fa-github', 'library' => 'fa-brands' ],
                    ],
                ],
                'title_field' => '{{{ testimonial_author }}} - {{{ testimonial_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * CONTENT TAB: Layout & Carousel Settings
     *--------------------------------------------------------------------------*/
    protected function content_settings_controls() {
        $this->start_controls_section(
            'section_testimonial_settings',
            [
                'label' => esc_html__( 'Layout & Carousel Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'testimonial_image_size',
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            'testimonial_amount',
            [
                'label'          => esc_html__( 'Columns', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '2',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => esc_html__( '1 Column', 'ultraaddons-elementor-lite' ),
                    '2' => esc_html__( '2 Columns', 'ultraaddons-elementor-lite' ),
                    '3' => esc_html__( '3 Columns', 'ultraaddons-elementor-lite' ),
                    '4' => esc_html__( '4 Columns', 'ultraaddons-elementor-lite' ),
                    '5' => esc_html__( '5 Columns', 'ultraaddons-elementor-lite' ),
                    '6' => esc_html__( '6 Columns', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class'   => 'ua-testimonial-slider-columns%s-',
                'render_type'    => 'template',
                'frontend_available' => true,
                'separator'      => 'before',
            ]
        );

        $this->add_control(
            'testimonial_slides_to_scroll',
            [
                'label'              => esc_html__( 'Slides to Scroll', 'ultraaddons-elementor-lite' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 1,
                'max'                => 10,
                'default'            => 1,
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'testimonial_gutter',
            [
                'label'      => esc_html__( 'Gutter (Spacing)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [ 'size' => 15, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-carousel .slick-slide' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .ua-testimonial-carousel .slick-list'  => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        // Navigation Arrows
        $this->add_control(
            'testimonial_nav',
            [
                'label'        => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'selectors'    => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'display: flex !important;',
                ],
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'testimonial_nav_hover',
            [
                'label'        => esc_html__( 'Show Arrows on Hover Only', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'fade',
                'prefix_class' => 'ua-testimonial-nav-',
                'condition'    => [ 'testimonial_nav' => 'yes' ],
            ]
        );

        $this->add_control(
            'testimonial_nav_icon',
            [
                'label'     => esc_html__( 'Navigation Arrow Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fas fa-angle-left',
                'options'   => [
                    'fas fa-angle-left'          => esc_html__( 'Angle', 'ultraaddons-elementor-lite' ),
                    'fas fa-angle-double-left'   => esc_html__( 'Angle Double', 'ultraaddons-elementor-lite' ),
                    'fas fa-arrow-left'          => esc_html__( 'Arrow', 'ultraaddons-elementor-lite' ),
                    'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'ultraaddons-elementor-lite' ),
                    'fas fa-chevron-left'        => esc_html__( 'Chevron', 'ultraaddons-elementor-lite' ),
                    'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [ 'testimonial_nav' => 'yes' ],
            ]
        );

        // Pagination Dots
        $this->add_control(
            'testimonial_dots',
            [
                'label'        => esc_html__( 'Pagination Dots', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        // Autoplay & Loop
        $this->add_control(
            'testimonial_autoplay',
            [
                'label'        => esc_html__( 'Autoplay', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'testimonial_autoplay_duration',
            [
                'label'     => esc_html__( 'Autoplay Speed (Sec)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 4,
                'min'       => 1,
                'max'       => 20,
                'step'      => 0.5,
                'condition' => [ 'testimonial_autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'testimonial_pause_on_hover',
            [
                'label'        => esc_html__( 'Pause on Hover', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'condition'    => [ 'testimonial_autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'testimonial_loop',
            [
                'label'        => esc_html__( 'Infinite Loop', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'testimonial_effect',
            [
                'label'   => esc_html__( 'Transition Effect', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__( 'Slide', 'ultraaddons-elementor-lite' ),
                    'fade'  => esc_html__( 'Fade', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'testimonial_effect_duration',
            [
                'label'   => esc_html__( 'Transition Duration (Sec)', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0.7,
                'min'     => 0.1,
                'max'     => 5,
                'step'    => 0.1,
            ]
        );

        // Quote Icon
        $this->add_control(
            'testimonial_icon',
            [
                'label'     => esc_html__( 'Quote Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'options'   => [
                    'none'               => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'fas fa-quote-left'  => esc_html__( 'Quote Left', 'ultraaddons-elementor-lite' ),
                    'fas fa-quote-right' => esc_html__( 'Quote Right', 'ultraaddons-elementor-lite' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'testimonial_icon_position',
            [
                'label'     => esc_html__( 'Quote Icon Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top',
                'options'   => [
                    'top'   => esc_html__( 'Top of Content', 'ultraaddons-elementor-lite' ),
                    'inner' => esc_html__( 'Inside Content', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [ 'testimonial_icon!' => 'none' ],
            ]
        );

        // Star Rating
        $this->add_control(
            'testimonial_rating',
            [
                'label'        => esc_html__( 'Star Rating', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'testimonial_rating_scale',
            [
                'label'     => esc_html__( 'Rating Scale', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5,
                'min'       => 1,
                'max'       => 10,
                'condition' => [ 'testimonial_rating' => 'yes' ],
            ]
        );

        $this->add_control(
            'testimonial_rating_score_show',
            [
                'label'        => esc_html__( 'Show Score Number', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
                'condition'    => [ 'testimonial_rating' => 'yes' ],
            ]
        );

        $this->add_control(
            'testimonial_rating_style',
            [
                'label'     => esc_html__( 'Rating Icon Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'style_2',
                'options'   => [
                    'style_1' => esc_html__( 'FontAwesome Stars', 'ultraaddons-elementor-lite' ),
                    'style_2' => esc_html__( 'Unicode Stars', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [ 'testimonial_rating' => 'yes' ],
            ]
        );

        $this->add_control(
            'testimonial_unmarked_rating_style',
            [
                'label'     => esc_html__( 'Unmarked Star Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'outline',
                'options'   => [
                    'solid'   => [
                        'title' => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                    'outline' => [
                        'title' => esc_html__( 'Outline', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star-o',
                    ],
                ],
                'condition' => [ 'testimonial_rating' => 'yes' ],
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * STYLE TAB: General / Card Style
     *--------------------------------------------------------------------------*/
    protected function style_general_controls() {
        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__( 'Card Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'general_bg_color',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-testimonial-item',
            ]
        );

        $this->add_responsive_control(
            'general_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 10,
                    'right'  => 10,
                    'bottom' => 30,
                    'left'   => 10,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'general_border',
                'selector' => '{{WRAPPER}} .ua-testimonial-item',
            ]
        );

        $this->add_responsive_control(
            'general_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'general_box_shadow',
                'selector' => '{{WRAPPER}} .ua-testimonial-item',
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * STYLE TAB: Content Box (Bubble & Text)
     *--------------------------------------------------------------------------*/
    protected function style_content_controls() {
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content Box (Bubble)', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'content_bg_color',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-testimonial-content-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'content_box_shadow',
                'selector' => '{{WRAPPER}} .ua-testimonial-content-inner',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'    => 25,
                    'right'  => 25,
                    'bottom' => 25,
                    'left'   => 25,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_border',
                'selector' => '{{WRAPPER}} .ua-testimonial-content-inner',
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 8,
                    'right'  => 8,
                    'bottom' => 8,
                    'left'   => 8,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Triangle
        $this->add_control(
            'content_triangle',
            [
                'label'        => esc_html__( 'Speech Bubble Triangle', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
                'prefix_class' => 'ua-testimonial-triangle-',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'triangle_color',
            [
                'label'     => esc_html__( 'Triangle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-content-inner:before' => 'border-top-color: {{VALUE}} !important; border-bottom-color: {{VALUE}} !important;',
                ],
                'condition' => [ 'content_triangle' => 'yes' ],
            ]
        );

        // Quote Icon Styles
        $this->add_control(
            'icon_section_heading',
            [
                'label'     => esc_html__( 'Quote Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Quote Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#c1c1c1',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-testimonial-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 100 ] ],
                'default'    => [ 'size' => 32, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-testimonial-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_distance',
            [
                'label'      => esc_html__( 'Icon Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label'     => esc_html__( 'Icon Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Title Styles
        $this->add_control(
            'title_section_heading',
            [
                'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-testimonial-title',
            ]
        );

        $this->add_responsive_control(
            'title_distance',
            [
                'label'      => esc_html__( 'Title Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 8, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_align',
            [
                'label'     => esc_html__( 'Title Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Content Styles
        $this->add_control(
            'content_section_heading',
            [
                'label'     => esc_html__( 'Testimonial Content', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .ua-testimonial-content, {{WRAPPER}} .ua-testimonial-content p',
            ]
        );

        $this->add_responsive_control(
            'content_distance',
            [
                'label'      => esc_html__( 'Content Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_align',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Date Styles
        $this->add_control(
            'date_section_heading',
            [
                'label'     => esc_html__( 'Date / Time', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label'     => esc_html__( 'Date Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography',
                'selector' => '{{WRAPPER}} .ua-testimonial-date',
            ]
        );

        $this->add_control(
            'date_align',
            [
                'label'     => esc_html__( 'Date Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-date' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Star Rating Styles
        $this->add_control(
            'rating_section_heading',
            [
                'label'     => esc_html__( 'Rating Stars', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'rating_position',
            [
                'label'   => esc_html__( 'Rating Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'    => esc_html__( 'Top of Content', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom of Content', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label'     => esc_html__( 'Active Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFB800',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-rating i:before'         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-rating-icon .ua-rating-marked svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-rating-icon .ua-rating-marked i'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_unmarked_color',
            [
                'label'     => esc_html__( 'Unmarked Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E0E0E0',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-rating i'                 => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-rating-icon .ua-rating-unmarked svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ua-rating-icon .ua-rating-unmarked i'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_score_color',
            [
                'label'     => esc_html__( 'Score Number Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFB800',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-rating span.ua-rating-score' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_align',
            [
                'label'     => esc_html__( 'Rating Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-rating' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_size',
            [
                'label'      => esc_html__( 'Star Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
                'default'    => [ 'size' => 18, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-rating i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-testimonial-rating svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_gutter',
            [
                'label'      => esc_html__( 'Star Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 2, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-rating i'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-testimonial-rating svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_distance',
            [
                'label'      => esc_html__( 'Rating Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 8, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * STYLE TAB: Meta (Author, Job, Avatar, Logo)
     *--------------------------------------------------------------------------*/
    protected function style_meta_controls() {
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => esc_html__( 'Author & Meta Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'meta_position',
            [
                'label'          => esc_html__( 'Meta Position', 'ultraaddons-elementor-lite' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => 'bottom',
                'tablet_default' => 'bottom',
                'mobile_default' => 'bottom',
                'options'        => [
                    'top'    => esc_html__( 'Top', 'ultraaddons-elementor-lite' ),
                    'bottom' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ),
                    'left'   => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                    'right'  => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                    'extra'  => esc_html__( 'Extra / Outside', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class'   => 'ua-testimonial-meta-position%s-',
                'render_type'    => 'template',
            ]
        );

        $this->add_responsive_control(
            'meta_gutter',
            [
                'label'      => esc_html__( 'Meta Distance from Content', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-testimonial-meta-position-top .ua-testimonial-meta'    => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-testimonial-meta-position-bottom .ua-testimonial-meta' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-testimonial-meta-position-left .ua-testimonial-meta'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-testimonial-meta-position-right .ua-testimonial-meta'  => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_align',
            [
                'label'        => esc_html__( 'Meta Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'default'      => 'center',
                'options'      => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'prefix_class' => 'ua-testimonial-meta-align%s-',
            ]
        );

        $this->add_responsive_control(
            'meta_valign',
            [
                'label'        => esc_html__( 'Vertical Alignment', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'default'      => 'middle',
                'options'      => [
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
                'prefix_class' => 'ua-testimonial-meta-valign%s-',
                'condition'    => [
                    'meta_position' => [ 'left', 'right' ],
                ],
            ]
        );

        // Author Avatar Image
        $this->add_control(
            'image_heading',
            [
                'label'     => esc_html__( 'Author Avatar', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_position',
            [
                'label'        => esc_html__( 'Avatar Position', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::CHOOSE,
                'default'      => 'center',
                'options'      => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-h-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-h-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-h-align-right' ],
                ],
                'prefix_class' => 'ua-testimonial-image-position%s-',
            ]
        );

        $this->add_responsive_control(
            'image_valign',
            [
                'label'     => esc_html__( 'Avatar Vertical Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'center',
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
                'condition' => [
                    'image_position' => [ 'left', 'right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-image'      => 'align-self: {{VALUE}} !important;',
                    '{{WRAPPER}} .ua-testimonial-meta-inner' => 'align-items: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label'      => esc_html__( 'Avatar Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 20, 'max' => 200 ] ],
                'default'    => [ 'size' => 60, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_distance',
            [
                'label'      => esc_html__( 'Avatar Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}}.ua-testimonial-image-position-left .ua-testimonial-image'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-testimonial-image-position-right .ua-testimonial-image'  => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua-testimonial-image-position-center .ua-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'selector' => '{{WRAPPER}} .ua-testimonial-image img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Author Name
        $this->add_control(
            'name_heading',
            [
                'label'     => esc_html__( 'Author Name', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label'     => esc_html__( 'Name Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .ua-testimonial-name',
            ]
        );

        $this->add_responsive_control(
            'name_distance_bottom',
            [
                'label'      => esc_html__( 'Name Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 4, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Author Job
        $this->add_control(
            'job_heading',
            [
                'label'     => esc_html__( 'Job / Designation', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label'     => esc_html__( 'Job Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'selector' => '{{WRAPPER}} .ua-testimonial-job',
            ]
        );

        $this->add_responsive_control(
            'job_distance',
            [
                'label'      => esc_html__( 'Job Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 6, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-job' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Company Logo
        $this->add_control(
            'logo_heading',
            [
                'label'     => esc_html__( 'Company Logo', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'logo_width',
            [
                'label'      => esc_html__( 'Logo Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 20, 'max' => 300 ] ],
                'default'    => [ 'size' => 80, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-logo-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_distance',
            [
                'label'      => esc_html__( 'Logo Bottom Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 6, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-logo-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * STYLE TAB: Social Media Icons
     *--------------------------------------------------------------------------*/
    protected function style_social_controls() {
        $this->start_controls_section(
            'section_style_social',
            [
                'label' => esc_html__( 'Social Media Icons', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_social_style' );

        $this->start_controls_tab(
            'tab_social_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'social_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-testimonial-social svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_social_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'social_hover_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-testimonial-social:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'social_box_size',
            [
                'label'      => esc_html__( 'Box Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 28, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'social_size',
            [
                'label'      => esc_html__( 'Icon Font Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-social i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-testimonial-social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_gutter',
            [
                'label'      => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 6, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_border_type',
            [
                'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'none',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'social_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => 1,
                    'right'  => 1,
                    'bottom' => 1,
                    'left'   => 1,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'social_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'social_box_shadow',
                'selector' => '{{WRAPPER}} .ua-testimonial-social',
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * STYLE TAB: Navigation Arrows
     *--------------------------------------------------------------------------*/
    protected function style_nav_controls() {
        $this->start_controls_section(
            'section_style_nav',
            [
                'label'     => esc_html__( 'Navigation Arrows', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [ 'testimonial_nav' => 'yes' ],
            ]
        );

        $this->start_controls_tabs( 'tabs_nav_style' );

        $this->start_controls_tab(
            'tab_nav_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'nav_color',
            [
                'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-testimonial-arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e2e8f0',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label'     => esc_html__( 'Arrow Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-testimonial-arrow:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'nav_size',
            [
                'label'      => esc_html__( 'Box Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 36, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'nav_font_size',
            [
                'label'      => esc_html__( 'Arrow Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 14, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-arrow i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-testimonial-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'nav_border_type',
            [
                'label'     => esc_html__( 'Border Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'   => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'solid'  => esc_html__( 'Solid', 'ultraaddons-elementor-lite' ),
                    'double' => esc_html__( 'Double', 'ultraaddons-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'ultraaddons-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'ultraaddons-elementor-lite' ),
                ],
                'default'   => 'none',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'nav_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'    => [
                    'top'    => 1,
                    'right'  => 1,
                    'bottom' => 1,
                    'left'   => 1,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'nav_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_vertical_position',
            [
                'label'      => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                    'px' => [ 'min' => -100, 'max' => 500 ],
                ],
                'default'    => [ 'size' => 50, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-arrow' => 'top: {{SIZE}}{{UNIT}} !important;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'nav_horizontal_offset',
            [
                'label'      => esc_html__( 'Horizontal Offset', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => -50, 'max' => 100 ],
                ],
                'default'    => [ 'size' => 10, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-prev-arrow' => 'left: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .ua-testimonial-next-arrow' => 'right: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*--------------------------------------------------------------------------
     * STYLE TAB: Pagination Dots
     *--------------------------------------------------------------------------*/
    protected function style_dots_controls() {
        $this->start_controls_section(
            'section_style_dots',
            [
                'label'     => esc_html__( 'Pagination Dots', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [ 'testimonial_dots' => 'yes' ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'dots_bg_color',
            [
                'label'     => esc_html__( 'Dot Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-dot' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            [ 'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'dots_active_bg_color',
            [
                'label'     => esc_html__( 'Active Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-dots .slick-active .ua-testimonial-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_border_color',
            [
                'label'     => esc_html__( 'Active Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-testimonial-dots .slick-active .ua-testimonial-dot' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'dots_size',
            [
                'label'      => esc_html__( 'Dot Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 4, 'max' => 30 ] ],
                'default'    => [ 'size' => 8, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'dots_gutter',
            [
                'label'      => esc_html__( 'Dot Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [ 'size' => 6, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_vr',
            [
                'label'      => esc_html__( 'Vertical Offset (%)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [ 'min' => -20, 'max' => 120 ],
                    'px' => [ 'min' => -100, 'max' => 500 ],
                ],
                'default'    => [ 'size' => 100, 'unit' => '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-testimonial-dots' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER METHODS
     *========================================================================*/

    /**
     * Render Author Avatar
     */
    public function render_testimonial_image( $item ) {
        $settings = $this->get_settings_for_display();
        $image_src = '';

        if ( ! empty( $item['testimonial_image']['id'] ) ) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src( $item['testimonial_image']['id'], 'testimonial_image_size', $settings );
        }

        if ( empty( $image_src ) && ! empty( $item['testimonial_image']['url'] ) ) {
            $image_src = $item['testimonial_image']['url'];
        }

        if ( ! empty( $image_src ) ) : ?>
            <div class="ua-testimonial-image">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $item['testimonial_author'] ?? '' ); ?>">
            </div>
        <?php endif;
    }

    /**
     * Render Social Media Links for an item
     */
    public function render_social_media( $item, $item_count ) {
        $has_social = false;
        for ( $s = 1; $s <= 5; $s++ ) {
            if ( ! empty( $item["social_icon_{$s}"]['value'] ) ) {
                $has_social = true;
                break;
            }
        }

        if ( ! $has_social ) {
            return;
        }

        echo '<div class="ua-testimonial-social-media">';
        for ( $s = 1; $s <= 5; $s++ ) {
            if ( empty( $item["social_icon_{$s}"]['value'] ) ) {
                continue;
            }

            $url = ! empty( $item["social_url_{$s}"]['url'] ) ? $item["social_url_{$s}"]['url'] : '#';
            $target = ! empty( $item["social_url_{$s}"]['is_external'] ) ? ' target="_blank"' : '';
            $nofollow = ! empty( $item["social_url_{$s}"]['nofollow'] ) ? ' rel="nofollow"' : '';

            echo '<a class="ua-testimonial-social" href="' . esc_url( $url ) . '"' . $target . $nofollow . '>';
            Icons_Manager::render_icon( $item["social_icon_{$s}"], [ 'aria-hidden' => 'true' ] );
            echo '</a>';
        }
        echo '</div>';
    }

    /**
     * Render Author Meta (Name, Job, Logo, Socials)
     */
    public function render_testimonial_meta( $item, $item_count ) {
        ?>
        <div class="ua-testimonial-meta-content-wrap">
            <?php if ( ! empty( $item['testimonial_author'] ) ) : ?>
                <div class="ua-testimonial-name"><?php echo wp_kses_post( $item['testimonial_author'] ); ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $item['testimonial_job'] ) ) : ?>
                <div class="ua-testimonial-job"><?php echo wp_kses_post( $item['testimonial_job'] ); ?></div>
            <?php endif; ?>

            <?php
            if ( ! empty( $item['testimonial_logo_image']['url'] ) ) {
                $logo_element = 'div';
                $logo_attrs = ' class="ua-testimonial-logo-image"';

                if ( ! empty( $item['testimonial_logo_url']['url'] ) ) {
                    $logo_element = 'a';
                    $target = ! empty( $item['testimonial_logo_url']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow = ! empty( $item['testimonial_logo_url']['nofollow'] ) ? ' rel="nofollow"' : '';
                    $logo_attrs = ' class="ua-testimonial-logo-image" href="' . esc_url( $item['testimonial_logo_url']['url'] ) . '"' . $target . $nofollow;
                }

                echo '<' . $logo_element . $logo_attrs . '>';
                echo '<img src="' . esc_url( $item['testimonial_logo_image']['url'] ) . '" alt="' . esc_attr( $item['testimonial_author'] ?? '' ) . '">';
                echo '</' . $logo_element . '>';
            }

            $this->render_social_media( $item, $item_count );
            ?>
        </div>
        <?php
    }

    /**
     * Render Star Rating
     */
    public function render_testimonial_rating( $item ) {
        $settings = $this->get_settings_for_display();

        if ( 'yes' !== $settings['testimonial_rating'] || empty( $item['testimonial_rating_amount'] ) ) {
            return;
        }

        $rating_amount = floatval( $item['testimonial_rating_amount'] );
        $scale         = ! empty( $settings['testimonial_rating_scale'] ) ? intval( $settings['testimonial_rating_scale'] ) : 5;
        $round_rating  = intval( $rating_amount );
        $rating_style  = $settings['testimonial_rating_style'] ?? 'style_2';
        $unmarked_type = $settings['testimonial_unmarked_rating_style'] ?? 'outline';

        $star_icon_entity = ( 'outline' === $unmarked_type ) ? '&#9734;' : '&#9733;';

        ?>
        <div class="ua-testimonial-rating">
            <?php for ( $i = 1; $i <= $scale; $i++ ) : ?>
                <?php if ( 'style_1' === $rating_style ) : ?>
                    <?php if ( $i <= $rating_amount ) : ?>
                        <span class="ua-rating-icon ua-rating-icon-full">
                            <span class="ua-rating-marked"><i class="fas fa-star" aria-hidden="true"></i></span>
                        </span>
                    <?php elseif ( $i === $round_rating + 1 && $rating_amount !== floatval( $round_rating ) ) : ?>
                        <?php $fraction = round( ( $rating_amount - $round_rating ) * 10 ); ?>
                        <span class="ua-rating-icon ua-rating-icon-<?php echo esc_attr( $fraction ); ?>">
                            <span class="ua-rating-marked"><i class="fas fa-star" aria-hidden="true"></i></span>
                            <span class="ua-rating-unmarked"><i class="<?php echo ( 'outline' === $unmarked_type ) ? 'far' : 'fas'; ?> fa-star" aria-hidden="true"></i></span>
                        </span>
                    <?php else : ?>
                        <span class="ua-rating-icon ua-rating-icon-empty">
                            <span class="ua-rating-unmarked"><i class="<?php echo ( 'outline' === $unmarked_type ) ? 'far' : 'fas'; ?> fa-star" aria-hidden="true"></i></span>
                        </span>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if ( $i <= $rating_amount ) : ?>
                        <i class="ua-rating-icon-full"><?php echo $star_icon_entity; ?></i>
                    <?php elseif ( $i === $round_rating + 1 && $rating_amount !== floatval( $round_rating ) ) : ?>
                        <?php $fraction = round( ( $rating_amount - $round_rating ) * 10 ); ?>
                        <i class="ua-rating-icon-<?php echo esc_attr( $fraction ); ?>"><?php echo $star_icon_entity; ?></i>
                    <?php else : ?>
                        <i class="ua-rating-icon-empty"><?php echo $star_icon_entity; ?></i>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ( 'yes' === ( $settings['testimonial_rating_score_show'] ?? '' ) ) : ?>
                <span class="ua-rating-score"><?php echo esc_html( number_format( $rating_amount, 1 ) ); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Testimonial Bubble Content
     */
    public function render_testimonial_content( $item ) {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="ua-testimonial-content-wrap">
            <div class="ua-testimonial-content-inner">
                <?php if ( ! empty( $settings['testimonial_icon'] ) && 'none' !== $settings['testimonial_icon'] && 'top' === ( $settings['testimonial_icon_position'] ?? 'top' ) ) : ?>
                    <div class="ua-testimonial-icon">
                        <i class="<?php echo esc_attr( $settings['testimonial_icon'] ); ?>"></i>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $item['testimonial_title'] ) ) : ?>
                    <div class="ua-testimonial-title"><?php echo wp_kses_post( $item['testimonial_title'] ); ?></div>
                <?php endif; ?>

                <?php if ( 'top' === ( $settings['rating_position'] ?? 'top' ) ) : ?>
                    <?php $this->render_testimonial_rating( $item ); ?>
                <?php endif; ?>

                <?php if ( ! empty( $item['testimonial_content'] ) ) : ?>
                    <div class="ua-testimonial-content">
                        <?php if ( ! empty( $settings['testimonial_icon'] ) && 'none' !== $settings['testimonial_icon'] && 'inner' === ( $settings['testimonial_icon_position'] ?? '' ) ) : ?>
                            <div class="ua-testimonial-icon">
                                <i class="<?php echo esc_attr( $settings['testimonial_icon'] ); ?>"></i>
                            </div>
                        <?php endif; ?>
                        <p><?php echo wp_kses_post( $item['testimonial_content'] ); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ( 'bottom' === ( $settings['rating_position'] ?? 'top' ) ) : ?>
                    <?php $this->render_testimonial_rating( $item ); ?>
                <?php endif; ?>

                <?php if ( ! empty( $item['testimonial_date'] ) ) : ?>
                    <div class="ua-testimonial-date"><?php echo esc_html( $item['testimonial_date'] ); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Main Render Method
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['testimonial_items'] ) ) {
            return;
        }

        $is_rtl    = is_rtl();
        $direction = $is_rtl ? 'rtl' : 'ltr';

        $autoplay_speed = ! empty( $settings['testimonial_autoplay_duration'] ) ? absint( floatval( $settings['testimonial_autoplay_duration'] ) * 1000 ) : 4000;
        $effect_speed   = ! empty( $settings['testimonial_effect_duration'] ) ? absint( floatval( $settings['testimonial_effect_duration'] ) * 1000 ) : 700;

        $columns          = ! empty( $settings['testimonial_amount'] ) ? intval( $settings['testimonial_amount'] ) : 2;
        $columns_tablet   = ! empty( $settings['testimonial_amount_tablet'] ) ? intval( $settings['testimonial_amount_tablet'] ) : ( $columns > 2 ? 2 : $columns );
        $columns_mobile   = ! empty( $settings['testimonial_amount_mobile'] ) ? intval( $settings['testimonial_amount_mobile'] ) : 1;
        $slides_to_scroll = ! empty( $settings['testimonial_slides_to_scroll'] ) ? intval( $settings['testimonial_slides_to_scroll'] ) : 1;

        $slick_options = [
            'rtl'                  => $is_rtl,
            'infinite'             => ( 'yes' === ( $settings['testimonial_loop'] ?? 'yes' ) ),
            'speed'                => $effect_speed,
            'arrows'               => ( 'yes' === ( $settings['testimonial_nav'] ?? 'yes' ) ),
            'dots'                 => ( 'yes' === ( $settings['testimonial_dots'] ?? 'yes' ) ),
            'autoplay'             => ( 'yes' === ( $settings['testimonial_autoplay'] ?? 'yes' ) ),
            'autoplaySpeed'        => $autoplay_speed,
            'pauseOnHover'         => ( 'yes' === ( $settings['testimonial_pause_on_hover'] ?? 'yes' ) ),
            'prevArrow'            => '#ua-testimonial-prev-' . esc_attr( $this->get_id() ),
            'nextArrow'            => '#ua-testimonial-next-' . esc_attr( $this->get_id() ),
            'slidesToShow'         => $columns,
            'slidesToScroll'       => $slides_to_scroll,
            'columnsTablet'        => $columns_tablet,
            'columnsMobile'        => $columns_mobile,
            'sliderSlidesToScroll' => $slides_to_scroll,
        ];

        $this->add_render_attribute( 'testimonial-carousel-attribute', [
            'class'             => 'ua-testimonial-carousel',
            'dir'               => esc_attr( $direction ),
            'data-slick'        => wp_json_encode( $slick_options ),
            'data-slide-effect' => esc_attr( $settings['testimonial_effect'] ?? 'slide' ),
        ] );

        $meta_position = $settings['meta_position'] ?? 'bottom';
        $nav_icon      = ! empty( $settings['testimonial_nav_icon'] ) ? $settings['testimonial_nav_icon'] : 'fas fa-angle-left';
        $prev_icon     = $nav_icon;
        $next_icon     = str_replace( 'left', 'right', $nav_icon );

        if ( $is_rtl ) {
            $temp_icon = $prev_icon;
            $prev_icon = $next_icon;
            $next_icon = $temp_icon;
        }
        ?>
        <div class="ua-testimonial-carousel-wrap">
            <div <?php echo $this->get_render_attribute_string( 'testimonial-carousel-attribute' ); ?>>
                <?php foreach ( $settings['testimonial_items'] as $key => $item ) : ?>
                    <div class="ua-testimonial-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ?? $key ); ?>">
                        
                        <div class="ua-testimonial-meta">
                            <div class="ua-testimonial-meta-inner">
                                <?php
                                $this->render_testimonial_image( $item );
                                if ( 'extra' !== $meta_position ) {
                                    $this->render_testimonial_meta( $item, $key );
                                }
                                ?>
                            </div>
                        </div>

                        <?php $this->render_testimonial_content( $item ); ?>

                        <?php if ( 'extra' === $meta_position ) : ?>
                            <div class="ua-testimonial-meta">
                                <div class="ua-testimonial-meta-inner">
                                    <?php $this->render_testimonial_meta( $item, $key ); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ( 'yes' === ( $settings['testimonial_dots'] ?? 'yes' ) ) : ?>
                <div class="ua-testimonial-controls">
                    <div class="ua-testimonial-dots"></div>
                </div>
            <?php endif; ?>

            <?php if ( 'yes' === ( $settings['testimonial_nav'] ?? 'yes' ) ) : ?>
                <div class="ua-testimonial-arrow-container">
                    <div class="ua-testimonial-prev-arrow ua-testimonial-arrow" id="ua-testimonial-prev-<?php echo esc_attr( $this->get_id() ); ?>" aria-label="Previous">
                        <i class="<?php echo esc_attr( $prev_icon ); ?>" aria-hidden="true"></i>
                    </div>
                    <div class="ua-testimonial-next-arrow ua-testimonial-arrow" id="ua-testimonial-next-<?php echo esc_attr( $this->get_id() ); ?>" aria-label="Next">
                        <i class="<?php echo esc_attr( $next_icon ); ?>" aria-hidden="true"></i>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
