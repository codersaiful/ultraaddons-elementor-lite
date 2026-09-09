<?php
/**
 * UltraAddons - Breadcrumb Navigation Widget
 *
 * An advanced, SEO-optimized, highly customizable Elementor Breadcrumbs widget.
 * Features unified WordPress & WooCommerce hierarchy engine, Google Schema JSON-LD,
 * title truncation with tooltip, mobile horizontal touch swipe, and 3 modern visual presets.
 *
 * @package UltraAddons
 * @since 1.0.0
 * @author UltraAddons Team
 */

namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Breadcrumb extends Base {

    /**
     * Get widget keywords.
     *
     * @return array
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'breadcrumb', 'breadcrumbs', 'nav', 'navigation', 'trail', 'seo', 'schema' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->register_content_general_controls();
        $this->register_content_archive_labels_controls();

        $this->register_style_box_controls();
        $this->register_style_items_controls();
        $this->register_style_separator_controls();
        $this->register_style_prefix_controls();
    }

    /**
     * General Content Controls.
     */
    protected function register_content_general_controls() {
        $this->start_controls_section(
            'ua_bc_section_general',
            [
                'label' => esc_html__( 'General Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_bc_preset',
            [
                'label'        => esc_html__( 'Design Preset', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'classic',
                'options'      => [
                    'classic' => esc_html__( 'Classic Minimal', 'ultraaddons-elementor-lite' ),
                    'pill'    => esc_html__( 'Modern Pill / Badge', 'ultraaddons-elementor-lite' ),
                    'arrow'   => esc_html__( 'Chevron Arrow Trail', 'ultraaddons-elementor-lite' ),
                ],
                'prefix_class' => 'ua-bc-preset-',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'ua_bc_home_display',
            [
                'label'   => esc_html__( 'Home Display', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text_only',
                'options' => [
                    'text_only' => esc_html__( 'Text Only', 'ultraaddons-elementor-lite' ),
                    'both'      => esc_html__( 'Icon & Text', 'ultraaddons-elementor-lite' ),
                    'icon_only' => esc_html__( 'Icon Only', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_bc_home_text',
            [
                'label'       => esc_html__( 'Home Label', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Home', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_bc_home_display' => [ 'both', 'text_only' ],
                ],
            ]
        );

        $this->add_control(
            'ua_bc_home_icon',
            [
                'label'       => esc_html__( 'Home Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    'ua_bc_home_display' => [ 'both', 'icon_only' ],
                ],
            ]
        );

        $this->add_control(
            'ua_bc_show_prefix',
            [
                'label'        => esc_html__( 'Show Prefix', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'ua_bc_prefix_type',
            [
                'label'     => esc_html__( 'Prefix Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'text',
                'options'   => [
                    'text' => esc_html__( 'Text Label', 'ultraaddons-elementor-lite' ),
                    'icon' => esc_html__( 'Icon Only', 'ultraaddons-elementor-lite' ),
                    'both' => esc_html__( 'Icon & Text', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_bc_show_prefix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_prefix_text',
            [
                'label'       => esc_html__( 'Prefix Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Browse:', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_bc_show_prefix' => 'yes',
                    'ua_bc_prefix_type' => [ 'text', 'both' ],
                ],
            ]
        );

        $this->add_control(
            'ua_bc_prefix_icon',
            [
                'label'       => esc_html__( 'Prefix Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-map-marker-alt',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    'ua_bc_show_prefix' => 'yes',
                    'ua_bc_prefix_type' => [ 'icon', 'both' ],
                ],
            ]
        );

        $this->add_control(
            'ua_bc_separator_type',
            [
                'label'     => esc_html__( 'Separator Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'icon' => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-nerd',
                    ],
                    'text' => [
                        'title' => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-area',
                    ],
                ],
                'default'   => 'icon',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_bc_separator_icon',
            [
                'label'       => esc_html__( 'Separator Icon', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    'ua_bc_separator_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_separator_text',
            [
                'label'       => esc_html__( 'Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__( '/', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'ai'          => [ 'active' => true ],
                'condition'   => [
                    'ua_bc_separator_type' => 'text',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Archive and Special Pages Labels Controls.
     */
    protected function register_content_archive_labels_controls() {
        $this->start_controls_section(
            'ua_bc_section_archive_labels',
            [
                'label' => esc_html__( 'Archive & Page Labels', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_bc_quote_archive_titles',
            [
                'label'        => esc_html__( 'Wrap Keywords in Quotes', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Applies quotes around search queries and tags.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_bc_category_prefix',
            [
                'label'       => esc_html__( 'Category Prefix', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Category: ', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ua_bc_tag_prefix',
            [
                'label'       => esc_html__( 'Tag Prefix', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Tag: ', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ua_bc_search_prefix',
            [
                'label'       => esc_html__( 'Search Prefix', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Search for: ', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ua_bc_author_prefix',
            [
                'label'       => esc_html__( 'Author Prefix', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Author: ', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ua_bc_404_title',
            [
                'label'       => esc_html__( '404 Error Page Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Page Not Found', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Controls: Container Box / Wrapper.
     */
    protected function register_style_box_controls() {
        $this->start_controls_section(
            'ua_bc_style_box',
            [
                'label' => esc_html__( 'Container Box', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_bc_alignment',
            [
                'label'   => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'ua_bc_box_background',
                'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ua-bc-trail',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_bc_box_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-bc-trail',
            ]
        );

        $this->add_responsive_control(
            'ua_bc_box_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-bc-trail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_bc_box_shadow',
                'selector' => '{{WRAPPER}} .ua-bc-trail',
            ]
        );

        $this->add_responsive_control(
            'ua_bc_box_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-bc-trail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_bc_box_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Controls: Breadcrumb Items & Links.
     */
    protected function register_style_items_controls() {
        $this->start_controls_section(
            'ua_bc_style_items',
            [
                'label' => esc_html__( 'Breadcrumb Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_bc_items_typography',
                'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item, {{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-link, {{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-current, {{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-title',
            ]
        );

        $this->add_responsive_control(
            'ua_bc_items_gap',
            [
                'label'      => esc_html__( 'Item Gap / Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-trail' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_bc_item_padding',
            [
                'label'      => esc_html__( 'Item Inner Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_bc_tabs_items' );

        // Normal State Tab
        $this->start_controls_tab(
            'ua_bc_tab_item_normal',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_bc_link_color',
            [
                'label'     => esc_html__( 'Link Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-arrow .ua-bc-item .ua-bc-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-pill .ua-bc-item .ua-bc-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_item_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-pill .ua-bc-item .ua-bc-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_bc_item_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item',
            ]
        );

        $this->add_responsive_control(
            'ua_bc_item_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'ua_bc_tab_item_hover',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_bc_link_color_hover',
            [
                'label'     => esc_html__( 'Link Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-arrow .ua-bc-item .ua-bc-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-pill .ua-bc-item .ua-bc-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_item_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-pill .ua-bc-item .ua-bc-link:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_item_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active / Current Page State Tab
        $this->start_controls_tab(
            'ua_bc_tab_item_active',
            [
                'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_bc_current_color',
            [
                'label'     => esc_html__( 'Active Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item-current .ua-bc-current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-arrow .ua-bc-item-current .ua-bc-current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-pill .ua-bc-item-current .ua-bc-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_current_bg',
            [
                'label'     => esc_html__( 'Active Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item-current' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap.ua-bc-preset-pill .ua-bc-item-current .ua-bc-current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_current_border_color',
            [
                'label'     => esc_html__( 'Active Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item-current' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_bc_current_typography',
                'label'    => esc_html__( 'Active Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item-current .ua-bc-current, {{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-item-current .ua-bc-title',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Controls: Separator.
     */
    protected function register_style_separator_controls() {
        $this->start_controls_section(
            'ua_bc_style_separator',
            [
                'label' => esc_html__( 'Separator', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_bc_separator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-sep' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-sep svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_bc_separator_size',
            [
                'label'      => esc_html__( 'Separator Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 6, 'max' => 36 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-sep' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-sep svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_bc_separator_spacing',
            [
                'label'      => esc_html__( 'Separator Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 6,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-sep' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Controls: Prefix.
     */
    protected function register_style_prefix_controls() {
        $this->start_controls_section(
            'ua_bc_style_prefix',
            [
                'label'     => esc_html__( 'Prefix', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_bc_show_prefix' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_bc_prefix_color',
            [
                'label'     => esc_html__( 'Prefix Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-prefix' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-prefix svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_bc_prefix_typography',
                'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-prefix',
            ]
        );

        $this->add_responsive_control(
            'ua_bc_prefix_gap',
            [
                'label'      => esc_html__( 'Gap after Prefix', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-breadcrumb-wrap .ua-bc-prefix' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Build the hierarchical breadcrumb items list.
     *
     * @param array $settings
     * @return array
     */
    protected function build_breadcrumb_items( $settings ) {
        $items = [];

        // 1. Home Item
        $home_display = ! empty( $settings['ua_bc_home_display'] ) ? $settings['ua_bc_home_display'] : 'both';
        $home_text    = ! empty( $settings['ua_bc_home_text'] ) ? $settings['ua_bc_home_text'] : esc_html__( 'Home', 'ultraaddons-elementor-lite' );
        $home_url     = apply_filters( 'ultraaddons_breadcrumb_home_url', home_url( '/' ) );

        $items[] = [
            'type'      => 'home',
            'title'     => $home_text,
            'url'       => $home_url,
            'is_active' => is_front_page() || is_home(),
        ];

        if ( is_front_page() || is_home() ) {
            return $items;
        }

        // 2. WooCommerce Shop Page
        if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
            $shop_page_id = wc_get_page_id( 'shop' );
            if ( $shop_page_id && ! is_shop() ) {
                $items[] = [
                    'type'      => 'shop',
                    'title'     => get_the_title( $shop_page_id ),
                    'url'       => get_permalink( $shop_page_id ),
                    'is_active' => false,
                ];
            }
        }

        // 3. Single WooCommerce Product
        if ( function_exists( 'is_product' ) && is_product() ) {
            global $post;
            $terms = wc_get_product_terms(
                $post->ID,
                'product_cat',
                apply_filters( 'woocommerce_breadcrumb_product_terms_args', [ 'orderby' => 'parent', 'order' => 'DESC' ] )
            );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $main_term = $terms[0];
                $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                $ancestors = array_reverse( $ancestors );

                foreach ( $ancestors as $ancestor_id ) {
                    $ancestor = get_term( $ancestor_id, 'product_cat' );
                    if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                        $items[] = [
                            'type'      => 'term',
                            'title'     => $ancestor->name,
                            'url'       => get_term_link( $ancestor ),
                            'is_active' => false,
                        ];
                    }
                }

                $items[] = [
                    'type'      => 'term',
                    'title'     => $main_term->name,
                    'url'       => get_term_link( $main_term ),
                    'is_active' => false,
                ];
            }

            $items[] = [
                'type'      => 'single',
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'is_active' => true,
            ];
            return $items;
        }

        // 4. WooCommerce Shop Archive
        if ( function_exists( 'is_shop' ) && is_shop() ) {
            $shop_page_id = wc_get_page_id( 'shop' );
            $items[] = [
                'type'      => 'shop',
                'title'     => $shop_page_id ? get_the_title( $shop_page_id ) : esc_html__( 'Shop', 'ultraaddons-elementor-lite' ),
                'url'       => $shop_page_id ? get_permalink( $shop_page_id ) : '',
                'is_active' => true,
            ];
            return $items;
        }

        // 5. Single Standard Post
        if ( is_single() ) {
            global $post;
            $post_type = get_post_type();

            if ( $post_type !== 'post' ) {
                $cpt_obj = get_post_type_object( $post_type );
                if ( $cpt_obj && $cpt_obj->has_archive ) {
                    $items[] = [
                        'type'      => 'cpt_archive',
                        'title'     => $cpt_obj->labels->name,
                        'url'       => get_post_type_archive_link( $post_type ),
                        'is_active' => false,
                    ];
                }
            } else {
                $categories = get_the_category( $post->ID );
                if ( ! empty( $categories ) ) {
                    // Yoast / RankMath primary category support
                    $primary_cat_id = get_post_meta( $post->ID, '_yoast_wpseo_primary_category', true );
                    if ( ! $primary_cat_id && class_exists( 'RankMath' ) ) {
                        $primary_cat_id = get_post_meta( $post->ID, 'rank_math_primary_category', true );
                    }

                    $selected_category = null;
                    if ( $primary_cat_id ) {
                        $selected_category = get_term( $primary_cat_id, 'category' );
                    }
                    if ( empty( $selected_category ) || is_wp_error( $selected_category ) ) {
                        $selected_category = $categories[0];
                    }

                    if ( $selected_category && ! is_wp_error( $selected_category ) ) {
                        $cat_ancestors = get_ancestors( $selected_category->term_id, 'category' );
                        $cat_ancestors = array_reverse( $cat_ancestors );

                        foreach ( $cat_ancestors as $ancestor_id ) {
                            $cat_term = get_term( $ancestor_id, 'category' );
                            if ( $cat_term && ! is_wp_error( $cat_term ) ) {
                                $items[] = [
                                    'type'      => 'category',
                                    'title'     => $cat_term->name,
                                    'url'       => get_category_link( $cat_term->term_id ),
                                    'is_active' => false,
                                ];
                            }
                        }

                        $items[] = [
                            'type'      => 'category',
                            'title'     => $selected_category->name,
                            'url'       => get_category_link( $selected_category->term_id ),
                            'is_active' => false,
                        ];
                    }
                }
            }

            $items[] = [
                'type'      => 'single',
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'is_active' => true,
            ];
            return $items;
        }

        // 6. Hierarchical Page
        if ( is_page() ) {
            global $post;
            if ( $post && $post->post_parent ) {
                $ancestors = get_post_ancestors( $post->ID );
                $ancestors = array_reverse( $ancestors );

                foreach ( $ancestors as $ancestor_id ) {
                    $items[] = [
                        'type'      => 'page',
                        'title'     => get_the_title( $ancestor_id ),
                        'url'       => get_permalink( $ancestor_id ),
                        'is_active' => false,
                    ];
                }
            }

            $items[] = [
                'type'      => 'page',
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'is_active' => true,
            ];
            return $items;
        }

        // 7. Category Archive
        if ( is_category() ) {
            $cat = get_queried_object();
            if ( $cat && ! is_wp_error( $cat ) ) {
                if ( $cat->parent ) {
                    $cat_ancestors = get_ancestors( $cat->term_id, 'category' );
                    $cat_ancestors = array_reverse( $cat_ancestors );

                    foreach ( $cat_ancestors as $ancestor_id ) {
                        $cat_term = get_term( $ancestor_id, 'category' );
                        if ( $cat_term && ! is_wp_error( $cat_term ) ) {
                            $items[] = [
                                'type'      => 'category',
                                'title'     => $cat_term->name,
                                'url'       => get_category_link( $cat_term->term_id ),
                                'is_active' => false,
                            ];
                        }
                    }
                }

                $prefix = ! empty( $settings['ua_bc_category_prefix'] ) ? $settings['ua_bc_category_prefix'] : '';
                $items[] = [
                    'type'      => 'category',
                    'title'     => $prefix . $cat->name,
                    'url'       => get_category_link( $cat->term_id ),
                    'is_active' => true,
                ];
            }
            return $items;
        }

        // 8. Tag Archive
        if ( is_tag() ) {
            $tag = get_queried_object();
            $quote = ( ! empty( $settings['ua_bc_quote_archive_titles'] ) && $settings['ua_bc_quote_archive_titles'] === 'yes' );
            $prefix = ! empty( $settings['ua_bc_tag_prefix'] ) ? $settings['ua_bc_tag_prefix'] : '';
            $title  = $quote ? '&ldquo;' . $tag->name . '&rdquo;' : $tag->name;

            $items[] = [
                'type'      => 'tag',
                'title'     => $prefix . $title,
                'url'       => get_tag_link( $tag->term_id ),
                'is_active' => true,
            ];
            return $items;
        }

        // 9. Taxonomy Archive
        if ( is_tax() ) {
            $term = get_queried_object();
            if ( $term && ! is_wp_error( $term ) ) {
                if ( $term->parent ) {
                    $ancestors = get_ancestors( $term->term_id, $term->taxonomy );
                    $ancestors = array_reverse( $ancestors );

                    foreach ( $ancestors as $ancestor_id ) {
                        $ancestor = get_term( $ancestor_id, $term->taxonomy );
                        if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                            $items[] = [
                                'type'      => 'term',
                                'title'     => $ancestor->name,
                                'url'       => get_term_link( $ancestor ),
                                'is_active' => false,
                            ];
                        }
                    }
                }

                $items[] = [
                    'type'      => 'term',
                    'title'     => $term->name,
                    'url'       => get_term_link( $term ),
                    'is_active' => true,
                ];
            }
            return $items;
        }

        // 10. Post Type Archive
        if ( is_post_type_archive() ) {
            $cpt_obj = get_queried_object();
            $items[] = [
                'type'      => 'cpt_archive',
                'title'     => $cpt_obj ? $cpt_obj->labels->name : post_type_archive_title( '', false ),
                'url'       => get_post_type_archive_link( get_query_var( 'post_type' ) ),
                'is_active' => true,
            ];
            return $items;
        }

        // 11. Author Archive
        if ( is_author() ) {
            $author = get_queried_object();
            $prefix = ! empty( $settings['ua_bc_author_prefix'] ) ? $settings['ua_bc_author_prefix'] : '';
            $items[] = [
                'type'      => 'author',
                'title'     => $prefix . ( $author ? $author->display_name : '' ),
                'url'       => $author ? get_author_posts_url( $author->ID ) : '',
                'is_active' => true,
            ];
            return $items;
        }

        // 12. Date Archives
        if ( is_date() ) {
            if ( is_day() ) {
                $items[] = [
                    'type'      => 'year',
                    'title'     => get_the_time( 'Y' ),
                    'url'       => get_year_link( get_the_time( 'Y' ) ),
                    'is_active' => false,
                ];
                $items[] = [
                    'type'      => 'month',
                    'title'     => get_the_time( 'F' ),
                    'url'       => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                    'is_active' => false,
                ];
                $items[] = [
                    'type'      => 'day',
                    'title'     => get_the_time( 'd' ),
                    'url'       => get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ),
                    'is_active' => true,
                ];
            } elseif ( is_month() ) {
                $items[] = [
                    'type'      => 'year',
                    'title'     => get_the_time( 'Y' ),
                    'url'       => get_year_link( get_the_time( 'Y' ) ),
                    'is_active' => false,
                ];
                $items[] = [
                    'type'      => 'month',
                    'title'     => get_the_time( 'F' ),
                    'url'       => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                    'is_active' => true,
                ];
            } elseif ( is_year() ) {
                $items[] = [
                    'type'      => 'year',
                    'title'     => get_the_time( 'Y' ),
                    'url'       => get_year_link( get_the_time( 'Y' ) ),
                    'is_active' => true,
                ];
            }
            return $items;
        }

        // 13. Search Results Page
        if ( is_search() ) {
            $quote = ( ! empty( $settings['ua_bc_quote_archive_titles'] ) && $settings['ua_bc_quote_archive_titles'] === 'yes' );
            $prefix = ! empty( $settings['ua_bc_search_prefix'] ) ? $settings['ua_bc_search_prefix'] : '';
            $search_term = get_search_query();
            $title = $quote ? '&ldquo;' . $search_term . '&rdquo;' : $search_term;

            $items[] = [
                'type'      => 'search',
                'title'     => $prefix . $title,
                'url'       => '',
                'is_active' => true,
            ];
            return $items;
        }

        // 14. 404 Error Page
        if ( is_404() ) {
            $title = ! empty( $settings['ua_bc_404_title'] ) ? $settings['ua_bc_404_title'] : esc_html__( 'Page Not Found', 'ultraaddons-elementor-lite' );
            $items[] = [
                'type'      => '404',
                'title'     => $title,
                'url'       => '',
                'is_active' => true,
            ];
            return $items;
        }

        return $items;
    }

    /**
     * Render Separator HTML.
     *
     * @param array $settings
     */
    protected function render_separator( $settings ) {
        $sep_type = ! empty( $settings['ua_bc_separator_type'] ) ? $settings['ua_bc_separator_type'] : 'icon';

        if ( $sep_type === 'text' && ( ! isset( $settings['ua_bc_separator_text'] ) || $settings['ua_bc_separator_text'] === '' ) ) {
            return;
        }
        ?>
        <li class="ua-bc-sep" aria-hidden="true">
            <?php
            if ( $sep_type === 'icon' && ! empty( $settings['ua_bc_separator_icon']['value'] ) ) {
                Icons_Manager::render_icon( $settings['ua_bc_separator_icon'], [ 'aria-hidden' => 'true' ] );
            } elseif ( $sep_type === 'text' && ! empty( $settings['ua_bc_separator_text'] ) ) {
                echo esc_html( $settings['ua_bc_separator_text'] );
            }
            ?>
        </li>
        <?php
    }

    /**
     * Render Prefix HTML.
     *
     * @param array $settings
     */
    protected function render_prefix( $settings ) {
        if ( empty( $settings['ua_bc_show_prefix'] ) || $settings['ua_bc_show_prefix'] !== 'yes' ) {
            return;
        }

        $prefix_type = ! empty( $settings['ua_bc_prefix_type'] ) ? $settings['ua_bc_prefix_type'] : 'text';
        ?>
        <div class="ua-bc-prefix">
            <?php
            if ( in_array( $prefix_type, [ 'icon', 'both' ], true ) && ! empty( $settings['ua_bc_prefix_icon']['value'] ) ) {
                echo '<span class="ua-bc-prefix-icon">';
                Icons_Manager::render_icon( $settings['ua_bc_prefix_icon'], [ 'aria-hidden' => 'true' ] );
                echo '</span>';
            }
            if ( in_array( $prefix_type, [ 'text', 'both' ], true ) && ! empty( $settings['ua_bc_prefix_text'] ) ) {
                echo '<span class="ua-bc-prefix-text">' . esc_html( $settings['ua_bc_prefix_text'] ) . '</span>';
            }
            ?>
        </div>
        <?php
    }

    /**
     * Render Google Schema JSON-LD Structured Data.
     *
     * @param array $items
     */
    protected function render_schema_json_ld( $items ) {
        if ( empty( $items ) ) {
            return;
        }

        $schema_list = [];
        $position = 1;

        foreach ( $items as $item ) {
            $crumb_data = [
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => wp_strip_all_tags( $item['title'] ),
            ];

            if ( ! empty( $item['url'] ) ) {
                $crumb_data['item'] = esc_url( $item['url'] );
            }

            $schema_list[] = $crumb_data;
            $position++;
        }

        $schema_data = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $schema_list,
        ];

        echo '<script type="application/ld+json">' . wp_json_encode( $schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
    }

    /**
     * Render Widget Output on Frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = $this->build_breadcrumb_items( $settings );

        if ( empty( $items ) ) {
            return;
        }

        $preset = ! empty( $settings['ua_bc_preset'] ) ? $settings['ua_bc_preset'] : 'classic';

        // Render Schema.org JSON-LD
        $this->render_schema_json_ld( $items );

        $wrap_classes = [
            'ua-breadcrumb-wrap',
            'ua-bc-preset-' . sanitize_html_class( $preset ),
        ];

        $total_items = count( $items );
        ?>
        <nav class="<?php echo esc_attr( implode( ' ', $wrap_classes ) ); ?>" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'ultraaddons-elementor-lite' ); ?>">
            <?php $this->render_prefix( $settings ); ?>

            <ol class="ua-bc-trail" itemscope itemtype="https://schema.org/BreadcrumbList">
                <?php
                foreach ( $items as $index => $item ) :
                    $is_first = ( $index === 0 );
                    $is_last  = ( $index === ( $total_items - 1 ) );
                    $display_title = $item['title'];

                    $item_classes = [ 'ua-bc-item' ];
                    if ( $is_first ) {
                        $item_classes[] = 'ua-bc-item-home';
                    }
                    if ( $item['is_active'] || $is_last ) {
                        $item_classes[] = 'ua-bc-item-current';
                    }
                    ?>
                    <li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <?php if ( ! $is_last && ! empty( $item['url'] ) ) : ?>
                            <a class="ua-bc-link" href="<?php echo esc_url( $item['url'] ); ?>" itemprop="item">
                                <?php
                                if ( $is_first ) {
                                    $home_display = ! empty( $settings['ua_bc_home_display'] ) ? $settings['ua_bc_home_display'] : 'both';
                                    if ( in_array( $home_display, [ 'both', 'icon_only' ], true ) && ! empty( $settings['ua_bc_home_icon']['value'] ) ) {
                                        echo '<span class="ua-bc-icon">';
                                        Icons_Manager::render_icon( $settings['ua_bc_home_icon'], [ 'aria-hidden' => 'true' ] );
                                        echo '</span>';
                                    }
                                    if ( in_array( $home_display, [ 'both', 'text_only' ], true ) ) {
                                        echo '<span class="ua-bc-title" itemprop="name">' . esc_html( $display_title ) . '</span>';
                                    }
                                } else {
                                    echo '<span class="ua-bc-title" itemprop="name">' . wp_kses_post( $display_title ) . '</span>';
                                }
                                ?>
                            </a>
                        <?php else : ?>
                            <span class="ua-bc-current" itemprop="name">
                                <?php
                                if ( $is_first ) {
                                    $home_display = ! empty( $settings['ua_bc_home_display'] ) ? $settings['ua_bc_home_display'] : 'both';
                                    if ( in_array( $home_display, [ 'both', 'icon_only' ], true ) && ! empty( $settings['ua_bc_home_icon']['value'] ) ) {
                                        echo '<span class="ua-bc-icon">';
                                        Icons_Manager::render_icon( $settings['ua_bc_home_icon'], [ 'aria-hidden' => 'true' ] );
                                        echo '</span>';
                                    }
                                    if ( in_array( $home_display, [ 'both', 'text_only' ], true ) ) {
                                        echo '<span class="ua-bc-title">' . esc_html( $display_title ) . '</span>';
                                    }
                                } else {
                                    echo wp_kses_post( $display_title );
                                }
                                ?>
                            </span>
                        <?php endif; ?>
                        <meta itemprop="position" content="<?php echo esc_attr( $index + 1 ); ?>" />
                    </li>

                    <?php
                    // Render Separator between items
                    if ( ! $is_last ) :
                        $this->render_separator( $settings );
                    endif;
                endforeach;
                ?>
            </ol>
        </nav>
        <?php
    }
}