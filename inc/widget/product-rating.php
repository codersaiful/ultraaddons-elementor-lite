<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons WooCommerce Product Rating Widget
 *
 * Dynamically displays WooCommerce product ratings with smart layout presets (inline, stacked, badge),
 * high-precision star rendering (full, half, empty), score formats, and flexible review count links.
 *
 * @since 1.1.0.14
 * @package UltraAddons
 */
class Product_Rating extends Base {

    /**
     * Constructor — register and enqueue widget style
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'ultraaddons-product-rating',
            ULTRA_ADDONS_ASSETS . 'css/widgets/product-rating.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'ultraaddons-product-rating' );
    }

    /**
     * Widget style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return [ 'ultraaddons-product-rating' ];
    }

    /**
     * Set widget keywords for Elementor editor search
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'wc', 'woocommerce', 'product', 'rating', 'reviews', 'stars', 'star rating', 'customer reviews', 'feedback', 'shop' ];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Tab
        $this->content_general_controls();

        // Style Tab
        $this->style_stars_controls();
        $this->style_score_controls();
        $this->style_review_count_controls();
        $this->style_badge_controls();
        $this->style_container_box_controls();
    }

    /**
     * Content Tab: General Controls
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'ua_pr_content_section',
            [
                'label' => esc_html__( 'Rating Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Optional Product ID
        $this->add_control(
            'ua_pr_product_id',
            [
                'label'       => esc_html__( 'Product ID (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'description' => esc_html__( 'Leave empty to automatically use current product context.', 'ultraaddons-elementor-lite' ),
                'default'     => '',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        // Layout Preset
        $this->add_control(
            'ua_pr_layout',
            [
                'label'   => esc_html__( 'Layout Preset', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline'     => esc_html__( 'Inline (Stars & Reviews in 1 Row)', 'ultraaddons-elementor-lite' ),
                    'stacked'    => esc_html__( 'Stacked (Stars on Top, Reviews Below)', 'ultraaddons-elementor-lite' ),
                    'badge'      => esc_html__( 'Rating Badge Pill (★ Score + Reviews)', 'ultraaddons-elementor-lite' ),
                    'stars_only' => esc_html__( 'Stars Only', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // Star Style
        $this->add_control(
            'ua_pr_star_style',
            [
                'label'     => esc_html__( 'Star Icon Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'modern',
                'options'   => [
                    'modern'      => esc_html__( 'Modern Vector Star (Sharp)', 'ultraaddons-elementor-lite' ),
                    'rounded'     => esc_html__( 'Rounded Vector Star', 'ultraaddons-elementor-lite' ),
                    'custom_icon' => esc_html__( 'Custom Icon (Icon Picker)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_pr_layout!' => 'badge',
                ],
            ]
        );

        // Custom Full Icon
        $this->add_control(
            'ua_pr_custom_icon_filled',
            [
                'label'     => esc_html__( 'Filled Star Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_pr_star_style' => 'custom_icon',
                    'ua_pr_layout!'    => 'badge',
                ],
            ]
        );

        // Custom Half Icon
        $this->add_control(
            'ua_pr_custom_icon_half',
            [
                'label'     => esc_html__( 'Half Star Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-star-half-alt',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_pr_star_style' => 'custom_icon',
                    'ua_pr_layout!'    => 'badge',
                ],
            ]
        );

        // Custom Empty Icon
        $this->add_control(
            'ua_pr_custom_icon_empty',
            [
                'label'     => esc_html__( 'Empty Star Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'far fa-star',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'ua_pr_star_style' => 'custom_icon',
                    'ua_pr_layout!'    => 'badge',
                ],
            ]
        );

        // Numeric Score Switcher
        $this->add_control(
            'ua_pr_show_score',
            [
                'label'        => esc_html__( 'Show Numeric Rating Score', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
                'condition'    => [
                    'ua_pr_layout!' => 'badge',
                ],
            ]
        );

        // Score Format
        $this->add_control(
            'ua_pr_score_format',
            [
                'label'     => esc_html__( 'Score Format', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'fraction',
                'options'   => [
                    'number'   => esc_html__( 'Number Only (4.5)', 'ultraaddons-elementor-lite' ),
                    'fraction' => esc_html__( 'Fraction (4.5 / 5)', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_pr_show_score' => 'yes',
                    'ua_pr_layout!'    => 'badge',
                ],
            ]
        );

        // Score Position
        $this->add_control(
            'ua_pr_score_position',
            [
                'label'     => esc_html__( 'Score Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'after',
                'options'   => [
                    'before' => esc_html__( 'Before Stars', 'ultraaddons-elementor-lite' ),
                    'after'  => esc_html__( 'After Stars', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_pr_show_score' => 'yes',
                    'ua_pr_layout!'    => 'badge',
                ],
            ]
        );

        // Review Count Switcher
        $this->add_control(
            'ua_pr_show_review_count',
            [
                'label'        => esc_html__( 'Show Customer Review Count', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'ua_pr_layout!' => 'stars_only',
                ],
            ]
        );

        // Review Count Format
        $this->add_control(
            'ua_pr_count_format',
            [
                'label'     => esc_html__( 'Review Count Format', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'parentheses',
                'options'   => [
                    'parentheses' => esc_html__( '(12 Customer Reviews)', 'ultraaddons-elementor-lite' ),
                    'short'       => esc_html__( '(12 Reviews)', 'ultraaddons-elementor-lite' ),
                    'count_only'  => esc_html__( '(12)', 'ultraaddons-elementor-lite' ),
                    'custom'      => esc_html__( 'Custom Text Format', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_pr_show_review_count' => 'yes',
                    'ua_pr_layout!'           => 'stars_only',
                ],
            ]
        );

        // Custom Format String
        $this->add_control(
            'ua_pr_custom_count_text',
            [
                'label'       => esc_html__( 'Custom Count Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Verified Ratings', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Text displayed after the review count number.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_pr_show_review_count' => 'yes',
                    'ua_pr_count_format'      => 'custom',
                    'ua_pr_layout!'           => 'stars_only',
                ],
            ]
        );

        // Scroll to Reviews Tab Anchor
        $this->add_control(
            'ua_pr_link_to_reviews',
            [
                'label'        => esc_html__( 'Click to Scroll to Reviews Tab', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'ua_pr_show_review_count' => 'yes',
                    'ua_pr_layout!'           => 'stars_only',
                ],
            ]
        );

        // Empty Review State Section
        $this->add_control(
            'ua_pr_empty_heading',
            [
                'label'     => esc_html__( 'Empty / Zero Reviews State', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_pr_show_empty_state',
            [
                'label'        => esc_html__( 'Show When Product Has 0 Reviews', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'ua_pr_empty_text',
            [
                'label'     => esc_html__( 'Zero Reviews Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'No customer reviews yet (Be the first to review)', 'ultraaddons-elementor-lite' ),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'ua_pr_show_empty_state' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Stars Styling Controls
     */
    protected function style_stars_controls() {
        $this->start_controls_section(
            'ua_pr_stars_style_section',
            [
                'label'     => esc_html__( 'Stars Styling', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pr_layout!' => 'badge',
                ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'ua_pr_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                        'title' => esc_html__( 'Space Between', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => 'left',
            ]
        );

        // Star Size
        $this->add_responsive_control(
            'ua_pr_star_size',
            [
                'label'      => esc_html__( 'Star Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                    'em' => [ 'min' => 0.5, 'max' => 4, 'step' => 0.1 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 17,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pr-star'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-pr-star svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Star Gap
        $this->add_responsive_control(
            'ua_pr_star_gap',
            [
                'label'      => esc_html__( 'Space Between Stars', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pr-stars-wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Filled Star Color
        $this->add_control(
            'ua_pr_filled_color',
            [
                'label'     => esc_html__( 'Filled Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-star.ua-pr-star-full'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pr-star.ua-pr-star-full svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        // Half Star Color
        $this->add_control(
            'ua_pr_half_color',
            [
                'label'     => esc_html__( 'Half Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-star.ua-pr-star-half'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pr-star.ua-pr-star-half svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        // Empty Star Color
        $this->add_control(
            'ua_pr_empty_color',
            [
                'label'     => esc_html__( 'Empty Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-star.ua-pr-star-empty'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pr-star.ua-pr-star-empty svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Numeric Score Controls
     */
    protected function style_score_controls() {
        $this->start_controls_section(
            'ua_pr_score_style_section',
            [
                'label'     => esc_html__( 'Numeric Score Styling', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pr_show_score' => 'yes',
                    'ua_pr_layout!'    => 'badge',
                ],
            ]
        );

        $this->add_control(
            'ua_pr_score_color',
            [
                'label'     => esc_html__( 'Score Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-score' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pr_score_typography',
                'selector' => '{{WRAPPER}} .ua-pr-score',
            ]
        );

        $this->add_responsive_control(
            'ua_pr_score_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pr-score' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Review Count & Text Controls
     */
    protected function style_review_count_controls() {
        $this->start_controls_section(
            'ua_pr_reviews_style_section',
            [
                'label'     => esc_html__( 'Review Count & Text', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pr_layout!' => 'stars_only',
                ],
            ]
        );

        $this->add_control(
            'ua_pr_reviews_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-review-link'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pr-no-review-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_pr_reviews_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-review-link:hover'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pr-no-review-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pr_reviews_typography',
                'selector' => '{{WRAPPER}} .ua-pr-review-link, {{WRAPPER}} .ua-pr-no-review-link',
            ]
        );

        $this->add_responsive_control(
            'ua_pr_reviews_spacing',
            [
                'label'      => esc_html__( 'Space Before Review Link', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 6,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pr-review-link'   => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-pr-no-review-link' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pr_layout' => [ 'inline', 'badge' ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Badge Pill Controls
     */
    protected function style_badge_controls() {
        $this->start_controls_section(
            'ua_pr_badge_style_section',
            [
                'label'     => esc_html__( 'Rating Badge Pill', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_pr_layout' => 'badge',
                ],
            ]
        );

        $this->add_control(
            'ua_pr_badge_bg',
            [
                'label'     => esc_html__( 'Badge Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#059669',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_pr_badge_text_color',
            [
                'label'     => esc_html__( 'Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pr-badge' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-pr-badge .ua-pr-badge-star' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_pr_badge_typography',
                'selector' => '{{WRAPPER}} .ua-pr-badge',
            ]
        );

        $this->add_responsive_control(
            'ua_pr_badge_radius',
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
                    '{{WRAPPER}} .ua-pr-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_pr_badge_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'default'    => [
                    'top'      => '4',
                    'right'    => '8',
                    'bottom'   => '4',
                    'left'     => '8',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pr-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab: Box Container Styling
     */
    protected function style_container_box_controls() {
        $this->start_controls_section(
            'ua_pr_box_style_section',
            [
                'label' => esc_html__( 'Box Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_pr_enable_box',
            [
                'label'        => esc_html__( 'Enable Container Box Style', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'ua_pr_box_bg',
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => '{{WRAPPER}} .ua-product-rating-wrapper.ua-product-rating-box',
                'condition' => [
                    'ua_pr_enable_box' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'ua_pr_box_border',
                'selector'  => '{{WRAPPER}} .ua-product-rating-wrapper.ua-product-rating-box',
                'condition' => [
                    'ua_pr_enable_box' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_pr_box_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-rating-wrapper.ua-product-rating-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pr_enable_box' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'ua_pr_box_shadow',
                'selector'  => '{{WRAPPER}} .ua-product-rating-wrapper.ua-product-rating-box',
                'condition' => [
                    'ua_pr_enable_box' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_pr_box_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-product-rating-wrapper.ua-product-rating-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_pr_enable_box' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render SVG Vector Star
     *
     * @param string $type 'full', 'half', or 'empty'
     * @param string $style 'modern' or 'rounded'
     * @return string SVG HTML
     */
    protected function render_svg_star( $type = 'full', $style = 'modern' ) {
        if ( 'rounded' === $style ) {
            switch ( $type ) {
                case 'half':
                    return '<svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4h7.6l-6.1 4.5 2.3 7.1-6.2-4.6v-14.4z"/><path fill-opacity="0.3" d="M12 2l-2.4 7.4h-7.6l6.1 4.5-2.3 7.1 6.2-4.6v-14.4z"/></svg>';
                case 'empty':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
                case 'full':
                default:
                    return '<svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
            }
        }

        // Modern Sharp Vector Stars
        switch ( $type ) {
            case 'half':
                return '<svg viewBox="0 0 576 512"><path d="M288 384.7V17.9c-2.4 0-4.8.6-7 1.7L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5v-55.1zm0-305.7l52.5 108.1c3.5 7.1 10.2 12.1 18.1 13.3l118.3 17.5-85.9 85.1c-5.5 5.5-8.1 13.3-6.8 21l20.2 119.6-105.2-56.2c-3.5-1.9-7.4-2.8-11.2-2.8V79z" fill-opacity="0.3"/><path d="M288 0c-12.3 0-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18C311.4 7 300.3 0 288 0z" clip-path="url(#ua-half-clip)"/><defs><clipPath id="ua-half-clip"><rect width="288" height="512"/></clipPath></defs></svg>';
            case 'empty':
                return '<svg viewBox="0 0 576 512"><path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/></svg>';
            case 'full':
            default:
                return '<svg viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>';
        }
    }

    /**
     * Render the 5 Stars with Full, Half, and Empty calculation
     *
     * @param float $average
     * @param array $settings
     * @return string HTML
     */
    protected function render_stars_html( $average, $settings ) {
        $star_style  = $settings['ua_pr_star_style'] ?? 'modern';
        $output_html = '<div class="ua-pr-stars-wrap">';

        for ( $i = 1; $i <= 5; $i++ ) {
            $diff = $average - ( $i - 1 );

            if ( $diff >= 0.75 ) {
                $state = 'full';
            } elseif ( $diff >= 0.25 ) {
                $state = 'half';
            } else {
                $state = 'empty';
            }

            $icon_markup = '';
            if ( 'custom_icon' === $star_style ) {
                $icon_key = 'ua_pr_custom_icon_' . $state;
                if ( ! empty( $settings[ $icon_key ] ) ) {
                    ob_start();
                    Icons_Manager::render_icon( $settings[ $icon_key ], [ 'aria-hidden' => 'true' ] );
                    $icon_markup = ob_get_clean();
                }
            }

            if ( empty( $icon_markup ) ) {
                $icon_markup = $this->render_svg_star( $state, $star_style );
            }

            $output_html .= sprintf(
                '<span class="ua-pr-star ua-pr-star-%s">%s</span>',
                esc_attr( $state ),
                $icon_markup
            );
        }

        $output_html .= '</div>';
        return $output_html;
    }

    /**
     * Main Widget Render
     */
    protected function render() {
        $is_editor = Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode();

        if ( ! function_exists( 'WC' ) ) {
            if ( $is_editor ) {
                ?>
                <div class="ua-woocommerce-warning" style="padding: 15px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 4px;">
                    <strong><?php esc_html_e( 'WooCommerce Not Active:', 'ultraaddons-elementor-lite' ); ?></strong>
                    <?php esc_html_e( 'Please install and activate WooCommerce to use the Product Rating widget.', 'ultraaddons-elementor-lite' ); ?>
                </div>
                <?php
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        // Resolve WooCommerce Product
        global $product;
        $current_product = $product;

        $custom_product_id = ! empty( $settings['ua_pr_product_id'] ) ? absint( $settings['ua_pr_product_id'] ) : 0;
        if ( $custom_product_id ) {
            $current_product = wc_get_product( $custom_product_id );
        }

        if ( ! is_a( $current_product, 'WC_Product' ) ) {
            $current_product = wc_get_product( get_the_ID() );
        }

        $average_rating = 0.0;
        $rating_count   = 0;
        $review_count   = 0;

        if ( $current_product ) {
            $average_rating = (float) $current_product->get_average_rating();
            $rating_count   = (int) $current_product->get_rating_count();
            $review_count   = (int) $current_product->get_review_count();
        } elseif ( $is_editor ) {
            // Realistic Editor Mock
            $average_rating = 4.5;
            $rating_count   = 12;
            $review_count   = 12;
        } else {
            return;
        }

        $has_reviews      = $rating_count > 0;
        $show_empty_state = 'yes' === ( $settings['ua_pr_show_empty_state'] ?? 'yes' );

        // If product has no reviews and empty state is turned off, return nothing on frontend
        if ( ! $has_reviews && ! $show_empty_state && ! $is_editor ) {
            return;
        }

        $layout    = $settings['ua_pr_layout'] ?? 'inline';
        $alignment = $settings['ua_pr_alignment'] ?? 'left';
        $is_box    = 'yes' === ( $settings['ua_pr_enable_box'] ?? '' );

        // Wrapper classes
        $wrapper_classes = [
            'ua-product-rating-wrapper',
            'ua-rating-layout-' . sanitize_html_class( $layout ),
            'ua-align-' . sanitize_html_class( $alignment ),
        ];

        if ( $is_box ) {
            $wrapper_classes[] = 'ua-product-rating-box';
        }

        $this->add_render_attribute( 'ua_rating_wrapper', 'class', $wrapper_classes );

        // Build Review Count Text
        $count_format   = $settings['ua_pr_count_format'] ?? 'parentheses';
        $review_label   = '';

        if ( 'parentheses' === $count_format ) {
            $review_label = $review_count > 1
                ? sprintf( esc_html__( '(%s Customer Reviews)', 'ultraaddons-elementor-lite' ), '<span class="ua-pr-count-num">' . esc_html( $review_count ) . '</span>' )
                : sprintf( esc_html__( '(%s Customer Review)', 'ultraaddons-elementor-lite' ), '<span class="ua-pr-count-num">' . esc_html( $review_count ) . '</span>' );
        } elseif ( 'short' === $count_format ) {
            $review_label = $review_count > 1
                ? sprintf( esc_html__( '(%s Reviews)', 'ultraaddons-elementor-lite' ), '<span class="ua-pr-count-num">' . esc_html( $review_count ) . '</span>' )
                : sprintf( esc_html__( '(%s Review)', 'ultraaddons-elementor-lite' ), '<span class="ua-pr-count-num">' . esc_html( $review_count ) . '</span>' );
        } elseif ( 'count_only' === $count_format ) {
            $review_label = sprintf( '(%s)', '<span class="ua-pr-count-num">' . esc_html( $review_count ) . '</span>' );
        } elseif ( 'custom' === $count_format ) {
            $custom_suffix = $settings['ua_pr_custom_count_text'] ?? '';
            $review_label  = sprintf( '%s %s', '<span class="ua-pr-count-num">' . esc_html( $review_count ) . '</span>', esc_html( $custom_suffix ) );
        }

        // Anchor Link Target
        $link_to_reviews = 'yes' === ( $settings['ua_pr_link_to_reviews'] ?? 'yes' );
        $link_href       = $link_to_reviews ? '#reviews' : 'javascript:void(0);';

        // Score markup
        $show_score   = 'yes' === ( $settings['ua_pr_show_score'] ?? '' );
        $score_format = $settings['ua_pr_score_format'] ?? 'fraction';
        $score_html   = '';

        if ( $show_score && $has_reviews ) {
            $score_val = number_format( (float) $average_rating, 1 );
            if ( 'fraction' === $score_format ) {
                $score_html = sprintf(
                    '<span class="ua-pr-score"><strong class="ua-pr-score-val">%s</strong><span class="ua-pr-score-max">/5</span></span>',
                    esc_html( $score_val )
                );
            } else {
                $score_html = sprintf(
                    '<span class="ua-pr-score"><strong class="ua-pr-score-val">%s</strong></span>',
                    esc_html( $score_val )
                );
            }
        }

        // Stars Markup
        $stars_html = $this->render_stars_html( $average_rating, $settings );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'ua_rating_wrapper' ); ?>>

            <?php if ( ! $has_reviews && ! $is_editor ) : ?>
                <!-- Zero Reviews State -->
                <div class="ua-pr-empty-wrap">
                    <?php echo $stars_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <a href="#review_form" class="ua-pr-no-review-link" rel="nofollow">
                        <?php echo esc_html( $settings['ua_pr_empty_text'] ?? esc_html__( 'No reviews yet (Be the first to review)', 'ultraaddons-elementor-lite' ) ); ?>
                    </a>
                </div>

            <?php elseif ( 'badge' === $layout ) : ?>
                <!-- Rating Badge Pill Layout -->
                <span class="ua-pr-badge">
                    <span class="ua-pr-badge-val"><?php echo esc_html( number_format( (float) $average_rating, 1 ) ); ?></span>
                    <span class="ua-pr-badge-star"><svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></span>
                </span>
                <?php if ( 'yes' === ( $settings['ua_pr_show_review_count'] ?? 'yes' ) && ! empty( $review_label ) ) : ?>
                    <a href="<?php echo esc_attr( $link_href ); ?>" class="ua-pr-review-link" rel="nofollow">
                        <?php echo $review_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                <?php endif; ?>

            <?php elseif ( 'stacked' === $layout ) : ?>
                <!-- Stacked Layout -->
                <div class="ua-pr-top-row">
                    <?php
                    if ( 'before' === ( $settings['ua_pr_score_position'] ?? 'after' ) && ! empty( $score_html ) ) {
                        echo $score_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    echo $stars_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    if ( 'after' === ( $settings['ua_pr_score_position'] ?? 'after' ) && ! empty( $score_html ) ) {
                        echo $score_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    ?>
                </div>
                <?php if ( 'yes' === ( $settings['ua_pr_show_review_count'] ?? 'yes' ) && ! empty( $review_label ) ) : ?>
                    <a href="<?php echo esc_attr( $link_href ); ?>" class="ua-pr-review-link" rel="nofollow">
                        <?php echo $review_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                <?php endif; ?>

            <?php elseif ( 'stars_only' === $layout ) : ?>
                <!-- Stars Only Layout -->
                <?php echo $stars_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

            <?php else : ?>
                <!-- Inline Layout (Default) -->
                <?php
                if ( 'before' === ( $settings['ua_pr_score_position'] ?? 'after' ) && ! empty( $score_html ) ) {
                    echo $score_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                echo $stars_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                if ( 'after' === ( $settings['ua_pr_score_position'] ?? 'after' ) && ! empty( $score_html ) ) {
                    echo $score_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
                <?php if ( 'yes' === ( $settings['ua_pr_show_review_count'] ?? 'yes' ) && ! empty( $review_label ) ) : ?>
                    <a href="<?php echo esc_attr( $link_href ); ?>" class="ua-pr-review-link" rel="nofollow">
                        <?php echo $review_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                <?php endif; ?>

            <?php endif; ?>

        </div>
        <?php
    }
}
