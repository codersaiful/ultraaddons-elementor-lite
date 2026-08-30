<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons - Pricing Table Widget
 *
 * A modern, conversion-optimized, and feature-packed Pricing Table widget.
 * Features:
 * - Interactive Monthly/Yearly Billing Cycle Switcher with discount badge
 * - Included vs Excluded feature items with custom icons
 * - Hover Tooltip on individual feature items
 * - Regular & Strike-through Discount Price
 * - Corner Ribbon, Floating Pill Badge, and Header Flag styles
 * - Featured / Popular Card pop-out mode with scale and glow
 * - Comprehensive Elementor typography, background, border, and shadow styling
 *
 * @package UltraAddons
 * @version 2.0.3.6
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Pricing_Table extends Base {

    /**
     * Constructor: Register widget styles and scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/pricing-table.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-pricing-table',
            ULTRA_ADDONS_ASSETS . 'css/widgets/pricing-table.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-pricing-table.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-pricing-table-js',
            ULTRA_ADDONS_ASSETS . 'js/frontend-pricing-table.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [ 'pricing', 'pricing table', 'price', 'plan', 'table', 'subscription', 'monthly yearly', 'ultraaddons' ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), [
            'elementor-icons-fa-solid',
            'elementor-icons-fa-regular',
            'elementor-icons-fa-brands',
            'ultraaddons-pricing-table',
        ] );
    }

    /**
     * Script Dependencies.
     */
    public function get_script_depends() {
        return [
            'ultraaddons-pricing-table-js',
        ];
    }

    /**
     * Register Widget Controls.
     */
    protected function register_controls() {
        // Content Controls (Data / Content / Elements)
        $this->content_header_controls();
        $this->content_pricing_controls();
        $this->content_switcher_controls();
        $this->content_features_controls();
        $this->content_button_controls();
        $this->content_ribbon_controls();

        // Style Controls (Visual Appearance / Typography / Colors / Alignment / Spacing)
        $this->style_card_controls();
        $this->style_header_controls();
        $this->style_pricing_controls();
        $this->style_switcher_controls();
        $this->style_features_controls();
        $this->style_button_controls();
        $this->style_ribbon_controls();
    }

    /**
     * Content: Header Section
     */
    protected function content_header_controls() {
        $this->start_controls_section(
            'section_content_header',
            [
                'label' => esc_html__( 'Header & Plan Info', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'plan_title',
            [
                'label'       => esc_html__( 'Plan Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Professional', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Starter, Pro, Business', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'plan_title_tag',
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
            'plan_subtitle',
            [
                'label'       => esc_html__( 'Subtitle / Description', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 2,
                'default'     => esc_html__( 'Best for small businesses and growing teams', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Short description of this plan', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'header_media_type',
            [
                'label'   => esc_html__( 'Header Icon / Media', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'none'  => [ 'title' => esc_html__( 'None', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-ban' ],
                    'icon'  => [ 'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-star' ],
                    'image' => [ 'title' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-image' ],
                ],
                'default' => 'icon',
            ]
        );

        $this->add_control(
            'header_icon',
            [
                'label'     => esc_html__( 'Select Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-rocket',
                    'library' => 'solid',
                ],
                'condition' => [
                    'header_media_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'header_image',
            [
                'label'     => esc_html__( 'Upload Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'header_media_type' => 'image',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Pricing & Period Section
     */
    protected function content_pricing_controls() {
        $this->start_controls_section(
            'section_content_pricing',
            [
                'label' => esc_html__( 'Pricing & Currency', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'currency_symbol',
            [
                'label'   => esc_html__( 'Currency Symbol', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '$',
            ]
        );

        $this->add_control(
            'monthly_price',
            [
                'label'       => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '29.99',
                'placeholder' => '29.99',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'monthly_orig_price',
            [
                'label'       => esc_html__( 'Original Price (Strike-through)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '49',
                'placeholder' => esc_html__( 'Leave empty to hide', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Shown with strike-through before current price to display discounts.', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'monthly_period',
            [
                'label'       => esc_html__( 'Period (e.g. /month)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '/mo',
                'placeholder' => '/mo',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'period_position',
            [
                'label'   => esc_html__( 'Period Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => esc_html__( 'Inline (Beside Price)', 'ultraaddons-elementor-lite' ),
                    'block'  => esc_html__( 'Block (Below Price)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Billing Switcher / Toggle Section
     */
    protected function content_switcher_controls() {
        $this->start_controls_section(
            'section_content_switcher',
            [
                'label' => esc_html__( 'Billing Cycle Switcher', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_switcher',
            [
                'label'        => esc_html__( 'Enable Switcher', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Enables an interactive switch for Monthly vs Yearly pricing.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'switcher_label_monthly',
            [
                'label'     => esc_html__( 'Label 1 (Primary / Monthly)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Monthly', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'switcher_label_yearly',
            [
                'label'     => esc_html__( 'Label 2 (Secondary / Yearly)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Yearly', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'yearly_price',
            [
                'label'       => esc_html__( 'Yearly Price', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '290.99',
                'placeholder' => '290.99',
                'condition'   => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'yearly_orig_price',
            [
                'label'       => esc_html__( 'Yearly Original Price (Strike-through)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '490',
                'placeholder' => '490',
                'condition'   => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'yearly_period',
            [
                'label'     => esc_html__( 'Yearly Period Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '/yr',
                'condition' => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'yearly_button_url',
            [
                'label'       => esc_html__( 'Yearly Button URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com/checkout-yearly',
                'condition'   => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'discount_badge_text',
            [
                'label'       => esc_html__( 'Discount Badge Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Save 20%', 'ultraaddons-elementor-lite' ),
                'placeholder' => 'Save 20%',
                'description' => esc_html__( 'Special tag displayed next to Yearly label.', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Features List Section
     */
    protected function content_features_controls() {
        $this->start_controls_section(
            'section_content_features',
            [
                'label' => esc_html__( 'Features List', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_text',
            [
                'label'       => esc_html__( 'Feature Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Feature item description', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'feature_status',
            [
                'label'   => esc_html__( 'Status', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'included',
                'options' => [
                    'included' => esc_html__( 'Included (Active)', 'ultraaddons-elementor-lite' ),
                    'excluded' => esc_html__( 'Excluded (Inactive / Grayed Out)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $repeater->add_control(
            'feature_icon',
            [
                'label'   => esc_html__( 'Custom Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-check',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'feature_tooltip',
            [
                'label'       => esc_html__( 'Tooltip Info Text (Optional)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Details popup on hover (e.g. 99.9% uptime SLA guarantee)', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'feature_highlight',
            [
                'label'        => esc_html__( 'Highlight Feature', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'features_list',
            [
                'label'       => esc_html__( 'Features', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'feature_text'    => esc_html__( 'Unlimited Products & Pages', 'ultraaddons-elementor-lite' ),
                        'feature_status'  => 'included',
                        'feature_icon'    => [ 'value' => 'fas fa-check', 'library' => 'solid' ],
                        'feature_tooltip' => '',
                    ],
                    [
                        'feature_text'    => esc_html__( 'Real-time Analytics Dashboard', 'ultraaddons-elementor-lite' ),
                        'feature_status'  => 'included',
                        'feature_icon'    => [ 'value' => 'fas fa-check', 'library' => 'solid' ],
                        'feature_tooltip' => esc_html__( 'Track visitor traffic and sales live in your admin dashboard.', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'feature_text'    => esc_html__( 'Advanced SEO & Schema Optimization', 'ultraaddons-elementor-lite' ),
                        'feature_status'  => 'included',
                        'feature_icon'    => [ 'value' => 'fas fa-check', 'library' => 'solid' ],
                        'feature_tooltip' => '',
                    ],
                    [
                        'feature_text'      => esc_html__( 'Dedicated 24/7 Priority Support', 'ultraaddons-elementor-lite' ),
                        'feature_status'    => 'included',
                        'feature_icon'      => [ 'value' => 'fas fa-check', 'library' => 'solid' ],
                        'feature_highlight' => 'yes',
                        'feature_tooltip'   => esc_html__( 'Get instant responses within 15 minutes from our senior engineers.', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'feature_text'    => esc_html__( 'Custom Domain & White Labeling', 'ultraaddons-elementor-lite' ),
                        'feature_status'  => 'excluded',
                        'feature_icon'    => [ 'value' => 'fas fa-times', 'library' => 'solid' ],
                        'feature_tooltip' => '',
                    ],
                ],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Action Button & Footer Notice Section
     */
    protected function content_button_controls() {
        $this->start_controls_section(
            'section_content_button',
            [
                'label' => esc_html__( 'Action Button & Footer', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Purchase Now', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Purchase Now, Buy Now', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label'       => esc_html__( 'Button Link (Monthly / Primary)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com/checkout',
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
                'label'   => esc_html__( 'Button Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
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
            ]
        );

        $this->add_control(
            'footer_notice',
            [
                'label'       => esc_html__( 'Footer Notice', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => esc_html__( '14-day money-back guarantee • No credit card required', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. 14-day money-back guarantee • No credit card required', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Content: Ribbon & Badges Section
     */
    protected function content_ribbon_controls() {
        $this->start_controls_section(
            'section_content_ribbon',
            [
                'label' => esc_html__( 'Ribbon & Badges', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_ribbon',
            [
                'label'        => esc_html__( 'Show Ribbon / Badge', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'ribbon_text',
            [
                'label'       => esc_html__( 'Ribbon Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Most Popular', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'e.g. Most Popular, Best Value, 30% Off', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'show_ribbon' => 'yes',
                ],
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ribbon_style',
            [
                'label'     => esc_html__( 'Ribbon Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'corner',
                'options'   => [
                    'corner' => esc_html__( 'Corner Diagonal Ribbon', 'ultraaddons-elementor-lite' ),
                    'pill'   => esc_html__( 'Top Floating Pill Badge', 'ultraaddons-elementor-lite' ),
                    'flag'   => esc_html__( 'Header Hanging Flag', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_ribbon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ribbon_corner_pos',
            [
                'label'     => esc_html__( 'Corner Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'right' => esc_html__( 'Top Right', 'ultraaddons-elementor-lite' ),
                    'left'  => esc_html__( 'Top Left', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'show_ribbon'  => 'yes',
                    'ribbon_style' => 'corner',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Card Container Section
     */
    protected function style_card_controls() {
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card Container', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_max_width',
            [
                'label'      => esc_html__( 'Card Max Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 200, 'max' => 900 ],
                    '%'  => [ 'min' => 20, 'max' => 100 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-card' => 'max-width: {{SIZE}}{{UNIT}}; width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_alignment',
            [
                'label'     => esc_html__( 'Card Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-table-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_background',
                'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-pricing-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .ua-pricing-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '16',
                    'right'    => '16',
                    'bottom'   => '16',
                    'left'     => '16',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '40',
                    'right'    => '32',
                    'bottom'   => '40',
                    'left'     => '32',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .ua-pricing-card',
            ]
        );

        $this->add_control(
            'card_hover_heading',
            [
                'label'     => esc_html__( 'Hover State', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_hover_box_shadow',
                'label'    => esc_html__( 'Hover Box Shadow', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-pricing-card:hover',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Header Section
     */
    protected function style_header_controls() {
        $this->start_controls_section(
            'section_style_header',
            [
                'label' => esc_html__( 'Header', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'header_align',
            [
                'label'     => esc_html__( 'Header Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-header' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-title' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-subtitle' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-subtitle',
            ]
        );

        $this->add_control(
            'icon_style_heading',
            [
                'label'     => esc_html__( 'Header Icon / Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'header_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 12, 'max' => 100 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 36 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-header-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-pricing-header-img'  => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-header-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Pricing Section
     */
    protected function style_pricing_controls() {
        $this->start_controls_section(
            'section_style_pricing',
            [
                'label' => esc_html__( 'Pricing', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pricing_align',
            [
                'label'     => esc_html__( 'Pricing Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-price-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Price Amount
        $this->add_control(
            'price_heading',
            [
                'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-amount',
            ]
        );

        // Currency Symbol
        $this->add_control(
            'currency_heading',
            [
                'label'     => esc_html__( 'Currency Symbol', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'currency_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-currency' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'currency_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 80 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 24 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-currency' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'currency_position',
            [
                'label'   => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Left (Before Price)', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'after'  => [
                        'title' => esc_html__( 'Right (After Price)', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'before',
            ]
        );

        $this->add_responsive_control(
            'currency_vertical_pos',
            [
                'label'     => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'top'    => [ 'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-top' ],
                    'middle' => [ 'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-middle' ],
                    'bottom' => [ 'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-bottom' ],
                ],
                'default'   => 'top',
                'selectors_dictionary' => [
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-currency' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'currency_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-currency' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'currency_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-currency',
            ]
        );

        // Sub Price
        $this->add_control(
            'sub_price_heading',
            [
                'label'     => esc_html__( 'Sub Price', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sub_price_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-sub-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_price_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 24 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-sub-price' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_price_vertical_pos',
            [
                'label'     => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'top'    => [ 'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-top' ],
                    'middle' => [ 'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-middle' ],
                    'bottom' => [ 'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-bottom' ],
                ],
                'default'   => 'top',
                'selectors_dictionary' => [
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-sub-price' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_price_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-sub-price' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_price_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-sub-price',
            ]
        );

        // Period / Duration
        $this->add_control(
            'period_heading',
            [
                'label'     => esc_html__( 'Period / Duration', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'period_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'period_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 15 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-period' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'period_vertical_pos',
            [
                'label'     => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'top'    => [ 'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-top' ],
                    'middle' => [ 'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-middle' ],
                    'bottom' => [ 'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-bottom' ],
                ],
                'default'   => 'bottom',
                'selectors_dictionary' => [
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-period' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'period_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-period' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'period_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-period',
            ]
        );

        // Strike-through Original Price
        $this->add_control(
            'orig_price_heading',
            [
                'label'     => esc_html__( 'Original Price (Strike-through)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'orig_price_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#94a3b8',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-original-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'orig_price_size',
            [
                'label'      => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'default'    => [ 'unit' => 'px', 'size' => 20 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-original-price' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'orig_price_vertical_pos',
            [
                'label'     => esc_html__( 'Vertical Position', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'top'    => [ 'title' => esc_html__( 'Top', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-top' ],
                    'middle' => [ 'title' => esc_html__( 'Middle', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-middle' ],
                    'bottom' => [ 'title' => esc_html__( 'Bottom', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-v-align-bottom' ],
                ],
                'default'   => 'middle',
                'selectors_dictionary' => [
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-original-price' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'orig_price_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-original-price',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Billing Switcher / Toggle Section
     */
    protected function style_switcher_controls() {
        $this->start_controls_section(
            'section_style_switcher',
            [
                'label'     => esc_html__( 'Billing Switcher', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'switcher_active_color',
            [
                'label'     => esc_html__( 'Active Switcher Track Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-toggle-switch input:checked + .ua-pricing-toggle-slider' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'switcher_inactive_color',
            [
                'label'     => esc_html__( 'Inactive Switcher Track Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-toggle-slider' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'switcher_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-switch-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'switcher_active_label_color',
            [
                'label'     => esc_html__( 'Active Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0f172a',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-switch-label.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'switcher_label_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-switch-label',
            ]
        );

        $this->add_control(
            'discount_badge_bg',
            [
                'label'     => esc_html__( 'Discount Badge Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e6f9f4',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-toggle-discount' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'discount_badge_color',
            [
                'label'     => esc_html__( 'Discount Badge Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-toggle-discount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Features List Section
     */
    protected function style_features_controls() {
        $this->start_controls_section(
            'section_style_features',
            [
                'label' => esc_html__( 'Features List', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'features_align',
            [
                'label'     => esc_html__( 'Features Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center'     => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'flex-end'   => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-features-list' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .ua-pricing-feature-item'  => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_item_color',
            [
                'label'     => esc_html__( 'Included Item Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-feature-item' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'feature_excluded_color',
            [
                'label'     => esc_html__( 'Excluded Item Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#94a3b8',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-feature-item.is-excluded' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'features_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-feature-item',
            ]
        );

        $this->add_control(
            'feature_icon_color',
            [
                'label'     => esc_html__( 'Included Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-feature-icon' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'feature_icon_excluded_color',
            [
                'label'     => esc_html__( 'Excluded Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-feature-item.is-excluded .ua-pricing-feature-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 8, 'max' => 40 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 16 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_row_gap',
            [
                'label'      => esc_html__( 'Row Spacing / Gap', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [ 'px' => [ 'min' => 4, 'max' => 40 ] ],
                'default'    => [ 'unit' => 'px', 'size' => 14 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-features-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_style_heading',
            [
                'label'     => esc_html__( 'Tooltip', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label'     => esc_html__( 'Tooltip Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-tooltip-content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-feature-tooltip-content::after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_text_color',
            [
                'label'     => esc_html__( 'Tooltip Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-feature-tooltip-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Action Button & Footer Notice Section
     */
    protected function style_button_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'Button & Footer', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_width_type',
            [
                'label'   => esc_html__( 'Button Width', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__( 'Auto (Fit to Content)', 'ultraaddons-elementor-lite' ),
                    'full' => esc_html__( 'Full Width (100%)', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-pricing-btn-width-',
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label'     => esc_html__( 'Button Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default'   => 'center',
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-footer' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'button_width_type!' => 'full',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        // Normal Tab
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-pricing-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .ua-pricing-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .ua-pricing-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_hover_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-pricing-btn:hover',
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__( 'Hover Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .ua-pricing-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '8',
                    'right'    => '8',
                    'bottom'   => '8',
                    'left'     => '8',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => '12',
                    'right'    => '32',
                    'bottom'   => '12',
                    'left'     => '32',
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-pricing-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'footer_notice_heading',
            [
                'label'     => esc_html__( 'Footer Notice', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'footer_notice_color',
            [
                'label'     => esc_html__( 'Notice Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#94a3b8',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-notice' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'footer_notice_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-notice',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style: Ribbon & Badges Section
     */
    protected function style_ribbon_controls() {
        $this->start_controls_section(
            'section_style_ribbon',
            [
                'label'     => esc_html__( 'Ribbon & Badge', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_ribbon' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ribbon_background',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-pricing-ribbon-corner .ribbon-inner, {{WRAPPER}} .ua-pricing-ribbon-pill, {{WRAPPER}} .ua-pricing-ribbon-flag',
            ]
        );

        $this->add_control(
            'ribbon_text_color',
            [
                'label'     => esc_html__( 'Ribbon Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-pricing-ribbon-corner .ribbon-inner, {{WRAPPER}} .ua-pricing-ribbon-pill, {{WRAPPER}} .ua-pricing-ribbon-flag' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ribbon_typography',
                'selector' => '{{WRAPPER}} .ua-pricing-ribbon-corner .ribbon-inner, {{WRAPPER}} .ua-pricing-ribbon-pill, {{WRAPPER}} .ua-pricing-ribbon-flag',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Helper to render individual feature icon reliably.
     */
    protected function render_feature_icon( $item, $status ) {
        $has_icon = false;

        if ( ! empty( $item['feature_icon'] ) ) {
            $icon = $item['feature_icon'];
            if ( is_array( $icon ) && ! empty( $icon['value'] ) ) {
                Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
                $has_icon = true;
            } elseif ( is_string( $icon ) && ! empty( $icon ) ) {
                echo '<i class="' . esc_attr( $icon ) . '" aria-hidden="true"></i>';
                $has_icon = true;
            }
        }

        if ( ! $has_icon ) {
            if ( 'excluded' === $status ) {
                echo '<i class="fas fa-times" aria-hidden="true"></i>';
            } else {
                echo '<i class="fas fa-check" aria-hidden="true"></i>';
            }
        }
    }

    /**
     * Parse a price string into integer and fractional sub-price parts.
     * E.g. '29.99' -> ['int' => '29', 'frac' => '.99']
     * E.g. '29' -> ['int' => '29', 'frac' => '']
     */
    protected function parse_price( $raw_price ) {
        $raw_price = trim( (string) $raw_price );
        if ( preg_match( '/^([^.,]+)[.,]([0-9]+)$/', $raw_price, $matches ) ) {
            return [
                'int'  => $matches[1],
                'frac' => '.' . $matches[2],
            ];
        }
        return [
            'int'  => $raw_price,
            'frac' => '',
        ];
    }

    /**
     * Render Output on Frontend.
     */
    protected function render() {
        wp_enqueue_style( 'ultraaddons-pricing-table' );
        wp_enqueue_script( 'ultraaddons-pricing-table-js' );

        $settings = $this->get_settings_for_display();

        // Header data
        $plan_title        = ! empty( $settings['plan_title'] ) ? $settings['plan_title'] : '';
        $plan_title_tag    = ! empty( $settings['plan_title_tag'] ) ? $settings['plan_title_tag'] : 'h3';
        $plan_subtitle     = ! empty( $settings['plan_subtitle'] ) ? $settings['plan_subtitle'] : '';
        $header_media_type = ! empty( $settings['header_media_type'] ) ? $settings['header_media_type'] : 'none';

        // Pricing data
        $currency_symbol   = ! empty( $settings['currency_symbol'] ) ? $settings['currency_symbol'] : '$';
        $currency_position = ! empty( $settings['currency_position'] ) ? $settings['currency_position'] : 'before';
        $monthly_price     = ! empty( $settings['monthly_price'] ) ? $settings['monthly_price'] : '0';
        $monthly_orig      = ! empty( $settings['monthly_orig_price'] ) ? $settings['monthly_orig_price'] : '';
        $monthly_period    = ! empty( $settings['monthly_period'] ) ? $settings['monthly_period'] : '';
        $period_position   = ! empty( $settings['period_position'] ) ? $settings['period_position'] : 'inline';

        $monthly_parts = $this->parse_price( $monthly_price );

        // Switcher data
        $enable_switcher    = ! empty( $settings['enable_switcher'] ) && 'yes' === $settings['enable_switcher'];
        $label_monthly      = ! empty( $settings['switcher_label_monthly'] ) ? $settings['switcher_label_monthly'] : esc_html__( 'Monthly', 'ultraaddons-elementor-lite' );
        $label_yearly       = ! empty( $settings['switcher_label_yearly'] ) ? $settings['switcher_label_yearly'] : esc_html__( 'Yearly', 'ultraaddons-elementor-lite' );
        $yearly_price       = ! empty( $settings['yearly_price'] ) ? $settings['yearly_price'] : $monthly_price;
        $yearly_orig        = ! empty( $settings['yearly_orig_price'] ) ? $settings['yearly_orig_price'] : '';
        $yearly_period      = ! empty( $settings['yearly_period'] ) ? $settings['yearly_period'] : '';
        $discount_badge     = ! empty( $settings['discount_badge_text'] ) ? $settings['discount_badge_text'] : '';
        $yearly_button_url  = ! empty( $settings['yearly_button_url']['url'] ) ? $settings['yearly_button_url']['url'] : '';

        $yearly_parts = $this->parse_price( $yearly_price );

        // Features data
        $features_list = ! empty( $settings['features_list'] ) && is_array( $settings['features_list'] ) ? $settings['features_list'] : [];

        // Button data
        $button_text          = ! empty( $settings['button_text'] ) ? $settings['button_text'] : '';
        $button_url           = ! empty( $settings['button_url']['url'] ) ? $settings['button_url']['url'] : '#';
        $button_icon_position = ! empty( $settings['button_icon_position'] ) ? $settings['button_icon_position'] : 'after';
        $footer_notice        = ! empty( $settings['footer_notice'] ) ? $settings['footer_notice'] : '';

        $button_attr = '';
        if ( ! empty( $settings['button_url']['is_external'] ) ) {
            $button_attr .= ' target="_blank"';
        }
        if ( ! empty( $settings['button_url']['nofollow'] ) ) {
            $button_attr .= ' rel="nofollow"';
        }

        // Ribbon data
        $show_ribbon       = ! empty( $settings['show_ribbon'] ) && 'yes' === $settings['show_ribbon'];
        $ribbon_text       = ! empty( $settings['ribbon_text'] ) ? $settings['ribbon_text'] : '';
        $ribbon_style      = ! empty( $settings['ribbon_style'] ) ? $settings['ribbon_style'] : 'corner';
        $ribbon_corner_pos = ! empty( $settings['ribbon_corner_pos'] ) ? $settings['ribbon_corner_pos'] : 'right';
        ?>

        <div class="ua-pricing-table-wrapper">

            <div class="ua-pricing-card"
                 data-monthly-price="<?php echo esc_attr( $monthly_parts['int'] ); ?>"
                 data-monthly-sub="<?php echo esc_attr( $monthly_parts['frac'] ); ?>"
                 data-monthly-period="<?php echo esc_attr( $monthly_period ); ?>"
                 data-monthly-orig="<?php echo esc_attr( $monthly_orig ); ?>"
                 data-monthly-url="<?php echo esc_url( $button_url ); ?>"
                 data-yearly-price="<?php echo esc_attr( $yearly_parts['int'] ); ?>"
                 data-yearly-sub="<?php echo esc_attr( $yearly_parts['frac'] ); ?>"
                 data-yearly-period="<?php echo esc_attr( $yearly_period ); ?>"
                 data-yearly-orig="<?php echo esc_attr( $yearly_orig ); ?>"
                 data-yearly-url="<?php echo esc_url( ! empty( $yearly_button_url ) ? $yearly_button_url : $button_url ); ?>">

                <!-- Corner Ribbon -->
                <?php if ( $show_ribbon && 'corner' === $ribbon_style && ! empty( $ribbon_text ) ) : ?>
                    <div class="ua-pricing-ribbon-corner corner-<?php echo esc_attr( $ribbon_corner_pos ); ?>" aria-hidden="true">
                        <span class="ribbon-inner"><?php echo esc_html( $ribbon_text ); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Header Flag Badge -->
                <?php if ( $show_ribbon && 'flag' === $ribbon_style && ! empty( $ribbon_text ) ) : ?>
                    <span class="ua-pricing-ribbon-flag"><?php echo esc_html( $ribbon_text ); ?></span>
                <?php endif; ?>

                <!-- Top Floating Pill Badge -->
                <?php if ( $show_ribbon && 'pill' === $ribbon_style && ! empty( $ribbon_text ) ) : ?>
                    <span class="ua-pricing-ribbon-pill"><?php echo esc_html( $ribbon_text ); ?></span>
                <?php endif; ?>

                <!-- Header Section -->
                <div class="ua-pricing-header">
                    <?php if ( 'icon' === $header_media_type ) : ?>
                        <div class="ua-pricing-header-icon" aria-hidden="true">
                            <?php 
                            if ( ! empty( $settings['header_icon']['value'] ) ) {
                                Icons_Manager::render_icon( $settings['header_icon'], [ 'aria-hidden' => 'true' ] );
                            } else {
                                echo '<i class="fas fa-rocket" aria-hidden="true"></i>';
                            }
                            ?>
                        </div>
                    <?php elseif ( 'image' === $header_media_type && ! empty( $settings['header_image']['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $settings['header_image']['url'] ); ?>" alt="<?php echo esc_attr( $plan_title ); ?>" class="ua-pricing-header-img">
                    <?php endif; ?>

                    <?php if ( ! empty( $plan_title ) ) : ?>
                        <<?php echo esc_attr( $plan_title_tag ); ?> class="ua-pricing-title">
                            <?php echo esc_html( $plan_title ); ?>
                        </<?php echo esc_attr( $plan_title_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $plan_subtitle ) ) : ?>
                        <p class="ua-pricing-subtitle"><?php echo esc_html( $plan_subtitle ); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Billing Cycle Switcher -->
                <?php if ( $enable_switcher ) : ?>
                    <div class="ua-pricing-switcher-wrap">
                        <span class="ua-pricing-switch-label ua-switch-monthly is-active"><?php echo esc_html( $label_monthly ); ?></span>
                        <label class="ua-pricing-toggle-switch">
                            <input type="checkbox" class="ua-pricing-toggle-checkbox">
                            <span class="ua-pricing-toggle-slider"></span>
                        </label>
                        <span class="ua-pricing-switch-label ua-switch-yearly">
                            <?php echo esc_html( $label_yearly ); ?>
                            <?php if ( ! empty( $discount_badge ) ) : ?>
                                <span class="ua-pricing-toggle-discount"><?php echo esc_html( $discount_badge ); ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Pricing Section -->
                <div class="ua-pricing-price-box">
                    <div class="ua-pricing-price-wrap">
                        <?php if ( ! empty( $monthly_orig ) ) : ?>
                            <span class="ua-pricing-original-price"><?php echo esc_html( ( 'before' === $currency_position ? $currency_symbol : '' ) . $monthly_orig . ( 'after' === $currency_position ? $currency_symbol : '' ) ); ?></span>
                        <?php endif; ?>

                        <?php if ( 'before' === $currency_position || 'super' === $currency_position ) : ?>
                            <span class="ua-pricing-currency <?php echo 'super' === $currency_position ? 'pos-super' : ''; ?>"><?php echo esc_html( $currency_symbol ); ?></span>
                        <?php endif; ?>

                        <span class="ua-pricing-amount"><?php echo esc_html( $monthly_parts['int'] ); ?></span>

                        <?php if ( ! empty( $monthly_parts['frac'] ) ) : ?>
                            <span class="ua-pricing-sub-price"><?php echo esc_html( $monthly_parts['frac'] ); ?></span>
                        <?php endif; ?>

                        <?php if ( 'after' === $currency_position ) : ?>
                            <span class="ua-pricing-currency"><?php echo esc_html( $currency_symbol ); ?></span>
                        <?php endif; ?>

                        <?php if ( ! empty( $monthly_period ) ) : ?>
                            <span class="ua-pricing-period period-<?php echo esc_attr( $period_position ); ?>"><?php echo esc_html( $monthly_period ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Features List Section -->
                <?php if ( ! empty( $features_list ) ) : ?>
                    <div class="ua-pricing-features">
                        <ul class="ua-pricing-features-list">
                            <?php foreach ( $features_list as $item ) :
                                $status       = ! empty( $item['feature_status'] ) ? $item['feature_status'] : 'included';
                                $is_highlight = ! empty( $item['feature_highlight'] ) && 'yes' === $item['feature_highlight'];
                                $tooltip      = ! empty( $item['feature_tooltip'] ) ? $item['feature_tooltip'] : '';

                                $item_classes = [ 'ua-pricing-feature-item', 'is-' . $status ];
                                if ( $is_highlight ) {
                                    $item_classes[] = 'is-highlighted';
                                }
                            ?>
                                <li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
                                    <span class="ua-pricing-feature-icon" aria-hidden="true">
                                        <?php $this->render_feature_icon( $item, $status ); ?>
                                    </span>

                                    <span class="ua-pricing-feature-text"><?php echo esc_html( $item['feature_text'] ?? '' ); ?></span>

                                    <?php if ( ! empty( $tooltip ) ) : ?>
                                        <span class="ua-feature-tooltip-wrap">
                                            <i class="ua-feature-tooltip-icon fas fa-info-circle" aria-hidden="true"></i>
                                            <span class="ua-feature-tooltip-content"><?php echo esc_html( $tooltip ); ?></span>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Action Button & Footer Notice -->
                <div class="ua-pricing-footer">
                    <?php if ( ! empty( $button_text ) ) : 
                        $button_width_type = ! empty( $settings['button_width_type'] ) ? $settings['button_width_type'] : 'auto';
                        $button_classes    = [ 'ua-pricing-btn', 'btn-' . $button_width_type . '-width' ];
                    ?>
                        <a href="<?php echo esc_url( $button_url ); ?>" class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>"<?php echo $button_attr; ?>>
                            <?php if ( 'before' === $button_icon_position && ! empty( $settings['button_icon']['value'] ) ) : ?>
                                <span class="btn-icon-before" aria-hidden="true">
                                    <?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </span>
                            <?php endif; ?>

                            <span class="ua-btn-text"><?php echo esc_html( $button_text ); ?></span>

                            <?php if ( 'after' === $button_icon_position ) : ?>
                                <span class="btn-icon-after" aria-hidden="true">
                                    <?php 
                                    if ( ! empty( $settings['button_icon']['value'] ) ) {
                                        Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] );
                                    } else {
                                        echo '<i class="fas fa-arrow-right" aria-hidden="true"></i>';
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( ! empty( $footer_notice ) ) : ?>
                        <p class="ua-pricing-notice"><?php echo esc_html( $footer_notice ); ?></p>
                    <?php endif; ?>
                </div>

            </div>

        </div>
        <?php
    }
}