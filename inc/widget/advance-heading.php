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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advance_Heading extends Base{
    
    /**
     * Get your widget keywords
     *
     * @since 1.0.0
     * @access public
     *
     * @return array keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite','ua', 'heading', 'header', 'title', 'advanced text', 'dual heading' ];
    }
    
    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        
        // For General Style Section
        $this->register_general_style();

        // For General Content Section
        $this->content_general_controls();

        // For Design Section Style Tab
        $this->register_heading_align_style();
        
        // For Typography Section Style Tab
        $this->style_typography_controls();
    }
    
    /**
     * Render widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes( 'avd_heading', 'none' );
        $this->add_render_attribute( 'avd_heading', 'class', 'heading-tag' );
        if ( ! empty( $settings['avd_style_mode'] ) && $settings['avd_style_mode'] === 'gradient' ) {
            $this->add_render_attribute( 'avd_heading', 'class', 'avd-gradient-text' );
        }

        $this->add_render_attribute( 'avd_sub_heading_wrapper', 'class', 'sub-heading-wrapper' );
        if ( ! empty( $settings['avd_show_sub_heading_line'] ) && 'yes' === $settings['avd_show_sub_heading_line'] ) {
            $this->add_render_attribute( 'avd_sub_heading_wrapper', 'class', 'has-decorative-line' );
        }
        $this->add_render_attribute( 'avd_sub_heading', 'class', 'spb' );
        $this->add_inline_editing_attributes( 'avd_sub_heading', 'none' );

        $alignment = ! empty( $settings['avd_heading_alignment'] ) ? $settings['avd_heading_alignment'] : 'left';
        $tag       = ! empty( $settings['avd_heading_tag'] ) ? $settings['avd_heading_tag'] : 'h2';
        $sub_pos   = ! empty( $settings['avd_sub_heading_position'] ) ? $settings['avd_sub_heading_position'] : 'after';

        $has_link = ! empty( $settings['avd_heading_link']['url'] );
        if ( $has_link ) {
            $this->add_link_attributes( 'heading_link', $settings['avd_heading_link'] );
            $this->add_render_attribute( 'heading_link', 'class', 'advance-heading-link' );
        }

        // Prepare main heading content
        $main_heading_text = ! empty( $settings['avd_heading'] ) ? $settings['avd_heading'] : ( ! empty( $settings['advance_heading'] ) ? $settings['advance_heading'] : '' );
        $sub_heading_text  = ! empty( $settings['avd_sub_heading'] ) ? $settings['avd_sub_heading'] : ( ! empty( $settings['advance_sub_heading'] ) ? $settings['advance_sub_heading'] : '' );
        $highlight_text    = ! empty( $settings['avd_highlight_text'] ) ? trim( $settings['avd_highlight_text'] ) : '';
        $style_mode        = ! empty( $settings['avd_style_mode'] ) ? $settings['avd_style_mode'] : 'normal';

        if ( ! empty( $main_heading_text ) ) {
            // Method 1: Check for {curly braces} in heading text (e.g. "We provide {best} services")
            if ( preg_match( '/\{([^}]+)\}/u', $main_heading_text ) ) {
                $main_heading_html = preg_replace( '/\{([^}]+)\}/u', '<span class="avd-highlight-text">$1</span>', esc_html( $main_heading_text ) );
            }
            // Method 2: Highlight Text input box (only when style_mode is 'highlight')
            elseif ( 'highlight' === $style_mode && ! empty( $highlight_text ) ) {
                $pos = mb_stripos( $main_heading_text, $highlight_text );
                if ( false !== $pos ) {
                    $len    = mb_strlen( $highlight_text );
                    $before = mb_substr( $main_heading_text, 0, $pos );
                    $match  = mb_substr( $main_heading_text, $pos, $len );
                    $after  = mb_substr( $main_heading_text, $pos + $len );

                    $main_heading_html = esc_html( $before ) . '<span class="avd-highlight-text">' . esc_html( $match ) . '</span>' . esc_html( $after );
                } else {
                    $main_heading_html = wp_kses_post( $main_heading_text );
                }
            } else {
                $main_heading_html = wp_kses_post( $main_heading_text );
            }
        } elseif ( 'highlight' === $style_mode && ! empty( $highlight_text ) ) {
            $main_heading_html = '<span class="avd-highlight-text">' . esc_html( $highlight_text ) . '</span>';
        } else {
            $main_heading_html = '';
        }

        ?>
        <div class="advance-heading-wrapper <?php echo esc_attr( $alignment ); ?>">
            <?php if ( $has_link ) : ?>
                <a <?php echo $this->get_render_attribute_string( 'heading_link' ); ?>>
            <?php endif; ?>

            <?php if ( 'before' === $sub_pos && ! empty( $sub_heading_text ) ) : ?>
                <span <?php echo $this->get_render_attribute_string( 'avd_sub_heading_wrapper' ); ?>>
                    <span <?php echo $this->get_render_attribute_string( 'avd_sub_heading' ); ?>><?php echo wp_kses_post( $sub_heading_text ); ?></span>
                </span>
            <?php endif; ?>

            <?php if ( ! empty( $main_heading_html ) ) : ?>
                <<?php echo esc_html( $tag ); ?> <?php echo $this->get_render_attribute_string( 'avd_heading' ); ?>>
                    <?php echo $main_heading_html; ?>
                </<?php echo esc_html( $tag ); ?>>
            <?php endif; ?>

            <?php if ( 'after' === $sub_pos && ! empty( $sub_heading_text ) ) : ?>
                <span <?php echo $this->get_render_attribute_string( 'avd_sub_heading_wrapper' ); ?>>
                    <span <?php echo $this->get_render_attribute_string( 'avd_sub_heading' ); ?>><?php echo wp_kses_post( $sub_heading_text ); ?></span>
                </span>
            <?php endif; ?>

            <?php if ( $has_link ) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'avd_general_content',
            [
                'label'     => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'avd_heading',
            [
                'label'         => esc_html__( 'Heading', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => __( 'Lorem Ipsum is simply dummy text', 'ultraaddons-elementor-lite' ),
                'default'       => __( 'Lorem Ipsum is simply dummy text', 'ultraaddons-elementor-lite' ),
                'description'   => __( 'Tip: Wrap any word in {curly braces} to highlight it directly (e.g. Lorem {Ipsum} text).', 'ultraaddons-elementor-lite' ),
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'avd_style_mode',
            [
                'label'   => esc_html__( 'Heading Style Mode', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'normal'    => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
                    'highlight' => esc_html__( 'Highlight Accent', 'ultraaddons-elementor-lite' ),
                    'gradient'  => esc_html__( 'Gradient Text Fill', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'normal',
            ]
        );

        $this->add_control(
            'avd_highlight_text',
            [
                'label'         => esc_html__( 'Highlight / Accent Text', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => __( 'e.g. dummy', 'ultraaddons-elementor-lite' ),
                'description'   => __( 'Type the word(s) from Heading that you want to highlight inside the sentence.', 'ultraaddons-elementor-lite' ),
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'avd_style_mode' => 'highlight',
                ],
            ]
        );
        
        $this->add_control(
            'avd_sub_heading',
            [
                'label'         => esc_html__( 'Sub Heading', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => __( 'e.g. Subtitle or Caption', 'ultraaddons-elementor-lite' ),
                'default'       => '',
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ],
                'separator'     => 'before',
            ]
        );

        $this->add_control(
            'avd_sub_heading_position',
            [
                'label'   => esc_html__( 'Sub Heading Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'before' => esc_html__( 'Above Main Heading', 'ultraaddons-elementor-lite' ),
                    'after'  => esc_html__( 'Below Main Heading', 'ultraaddons-elementor-lite' ),
                ],
                'default' => 'after',
                'condition' => [
                    'avd_sub_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'avd_show_sub_heading_line',
            [
                'label'        => esc_html__( 'Show Decorative Line', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'avd_sub_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'avd_heading_tag',
            [
                'label'   => esc_html__( 'HTML Tag', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
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
                'default'   => 'h2',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'avd_heading_link',
            [
                'label'       => __( 'Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => 'https://example.com',
                'separator'   => 'before',
            ]
        );
        
        $this->end_controls_section();
    }

    /**
     * Register General Style (Heading Style 01 / Style 02)
     * 
     * @since 1.0.0
     */
    protected function register_general_style() {
        $this->start_controls_section(
            'heading_style_settings',
            [
                'label'     => esc_html__( 'Heading Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'heading_style',
            [
                'label'         => esc_html__( 'Heading Style', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    '1' => esc_html__( 'Style 01', 'ultraaddons-elementor-lite' ),
                    '2' => esc_html__( 'Style 02', 'ultraaddons-elementor-lite' ),
                ],
                'default'       => '1',
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Register General Controls Fallback (Alias)
     * 
     * @since 1.0.0
     */
    protected function register_general_controls() {
        // Safe alias method for backward compatibility
    }
    
    /**
     * Alignment & Spacing Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        // Safe alias method for backward compatibility
    }

    protected function register_heading_align_style() {
        $this->start_controls_section(
            'advance_heading_general_setting',
            [
                'label'     => esc_html__( 'Design & Alignment', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'avd_heading_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle'  => true,
            ]
        );
        
        $this->add_responsive_control(
            'avd_head_v_space',
            [
                'label'   => __( 'Vertical Spacing', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avd_sub_heading_margin',
            [
                'label'      => __( 'Sub Heading Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'top'      => 10,
                    'right'    => 0,
                    'bottom'   => 10,
                    'left'     => 0,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: block;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'avd_head_h_space',
            [
                'label'   => __( 'Horizontal Spacing', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper span.spb' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'avd_line_height',
            [
                'label'   => __( 'Line Thickness', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper.right span:before,{{WRAPPER}} .advance-heading-wrapper.center span:before,{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'avd_line_length',
            [
                'label'      => __( 'Line Length', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:after, {{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Typography & Styling Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_typography_controls() {

        // Main Heading Style
        $this->start_controls_section(
            'mc_avd_heading_typography',
            [
                'label'     => esc_html__( 'Main Heading', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'avd_sub_heading_color',
            [
                'label'     => __( 'Heading Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .advance-heading-wrapper a' => 'color: {{VALUE}}',
                ],
                'default'   => '#021429',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'avd_heading_gradient',
                'label' => esc_html__( 'Gradient Text Fill', 'ultraaddons-elementor-lite' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .advance-heading-wrapper .avd-gradient-text',
                'condition' => [
                    'avd_style_mode' => 'gradient',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => 'Heading Typography',
                'selector' => '{{WRAPPER}} .advance-heading-wrapper .heading-tag',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_tag_padding',
            [
                'label'      => __( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_tag_margin',
            [
                'label'      => __( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'heading_tag_border',
                'label' => __( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .advance-heading-wrapper .heading-tag',
            ]
        );

        $this->end_controls_section();

        // Highlight Accent Style
        $this->start_controls_section(
            'avd_highlight_style',
            [
                'label'     => esc_html__( 'Highlight Accent Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'avd_highlight_text!' => '',
                ],
            ]
        );
        $this->add_control(
            'highlight_color',
            [
                'label'     => __( 'Highlight Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0fc392',
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper .avd-highlight-text' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag .avd-highlight-text' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_control(
            'highlight_bg_color',
            [
                'label'     => __( 'Highlight Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper .avd-highlight-text' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .advance-heading-wrapper .heading-tag .avd-highlight-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'highlight_typography',
                'label'    => __( 'Highlight Typography', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .advance-heading-wrapper .avd-highlight-text, {{WRAPPER}} .advance-heading-wrapper .heading-tag .avd-highlight-text',
            ]
        );
        $this->add_responsive_control(
            'highlight_padding',
            [
                'label'      => __( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .avd-highlight-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'highlight_radius',
            [
                'label'      => __( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .avd-highlight-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Sub Heading Style
        $this->start_controls_section(
            'avd_subhead_style',
            [
                'label'     => esc_html__( 'Sub Heading', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'avd_heading_color',
            [
                'label'     => __( 'Sub Heading & Line Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:after,{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper:before' => 'background-color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subhead_typography',
                'label'    => 'Sub Heading Typography',
                'selector' => '{{WRAPPER}} .advance-heading-wrapper span.sub-heading-wrapper',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Get Sub Heading Content.
     * 
     * @access public
     * 
     * @since 1.0.0
     */
    public function get_sub_heading_content() {
        $settings = $this->get_settings_for_display();
        $sub_heading = ! empty( $settings['avd_sub_heading'] ) ? $settings['avd_sub_heading'] : ( ! empty( $settings['advance_sub_heading'] ) ? $settings['advance_sub_heading'] : '' );
        if ( ! empty( $sub_heading ) ) : ?> 
            <span class="ultraaddons-sub-heading elementor-inline-editing"><?php echo esc_html( $sub_heading );?></span>
        <?php endif;
    }
    
    /**
     * Get Heading Content.
     * 
     * @access public
     * 
     * @since 1.0.0
     */
    public function get_heading_content() {
        $settings = $this->get_settings_for_display();
        $heading = ! empty( $settings['avd_heading'] ) ? $settings['avd_heading'] : ( ! empty( $settings['advance_heading'] ) ? $settings['advance_heading'] : '' );
        if ( ! empty( $heading ) ) : ?> 
            <h3 class="ultraaddons-heading elementor-inline-editing"><?php echo esc_html( $heading );?></h3>
        <?php endif;
    }
}
