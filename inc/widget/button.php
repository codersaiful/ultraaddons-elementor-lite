<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * UltraAddons Creative Button Widget
 *
 * Feature-rich button widget with creative hover effects (Winona, Ujarak, Wayra,
 * Tamaya, Rayen), 25+ hover.css animations, gradient backgrounds, secondary text,
 *
 * @since 1.2.0
 */
class Button extends Base {

    /**
     * Constructor — register hover.css dependency
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'hover-css',
            ULTRA_ADDONS_ASSETS . 'vendor/hover-css/css/hover-min.css',
            [],
            ULTRA_ADDONS_VERSION,
            'all'
        );
        wp_enqueue_style( 'hover-css' );

        //CSS file for dependency
		$ultraaddons_name           = 'hover-css';
        $css_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/hover-css/css/hover-min.css';
        $dependency     =  [];
        $version        = ULTRA_ADDONS_VERSION;
        $media  	= 'all';
        wp_register_style('hover-css', $css_file_url,$dependency,$version, $media ); //product-carousel

    }

    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), ['hover-css'] );

    }

    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'button', 'btn', 'cta', 'creative button', 'hover', 'action' ];
    }

    /**
     * Register all controls
     */
    protected function register_controls() {
        $this->content_controls();
        $this->style_effects_controls();
        $this->style_hover_animation_controls();
    }

    /*==========================================================================
     * CONTENT TAB — Button Content
     *========================================================================*/
    protected function content_controls() {
        $this->start_controls_section(
            '_ua_btn_content_section',
            [
                'label' => esc_html__( 'Button Content', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'default'     => 'Click Me!',
                'placeholder' => esc_html__( 'Enter button text', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_btn_secondary_text',
            [
                'label'       => esc_html__( 'Button Secondary Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
                'default'     => 'Go!',
                'placeholder' => esc_html__( 'Enter secondary text', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            '_ua_btn_link',
            [
                'label'       => esc_html__( 'Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ],
                ],
                'label_block' => true,
                'default'     => [ 'url' => '#', 'is_external' => '', ],
                'show_external' => true,
            ]
        );

        $this->add_control(
            '_ua_btn_icon',
            [
                'label'            => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => '_ua_btn_icon_old',
                'condition'        => [
                    '_ua_btn_effect!' => 'ua-btn--tamaya',
                ],
            ]
        );

        $this->add_control(
            '_ua_btn_icon_rotate',
            [
                'label'   => esc_html__( 'Icon Rotation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'range'   => [
                    'deg' => [ 'min' => -360, 'max' => 360, 'step' => 1 ],
                ],
                'default' => [ 'unit' => 'deg', 'size' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-icon-left svg, {{WRAPPER}} .ua-btn-icon-right svg' => 'rotate: {{SIZE}}deg;',
                    '{{WRAPPER}} .ua-btn-icon-left i, {{WRAPPER}} .ua-btn-icon-right i'     => 'rotate: {{SIZE}}deg;',
                ],
            ]
        );

        $this->add_control(
            '_ua_btn_icon_position',
            [
                'label'   => esc_html__( 'Icon Position', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'  => esc_html__( 'Before', 'ultraaddons-elementor-lite' ),
                    'right' => esc_html__( 'After', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    '_ua_btn_icon[value]!' => '',
                    '_ua_btn_effect!'      => 'ua-btn--tamaya',
                ],
            ]
        );

        $this->add_responsive_control(
            '_ua_btn_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'max' => 60 ] ],
                'condition' => [
                    '_ua_btn_icon[value]!' => '',
                    '_ua_btn_effect!'      => 'ua-btn--tamaya',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-icon-right' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-btn-icon-left'  => 'margin-right: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Button Effects & Styles
     *========================================================================*/
    protected function style_effects_controls() {
        $this->start_controls_section(
            '_ua_btn_style_section',
            [
                'label' => esc_html__( 'Button Effects & Styles', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Creative Button Effect
        $this->add_control(
            '_ua_btn_effect',
            [
                'label'   => esc_html__( 'Set Button Effect', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ua-btn--default',
                'options' => [
                    'ua-btn--default' => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
                    'ua-btn--winona'  => esc_html__( 'Winona', 'ultraaddons-elementor-lite' ),
                    'ua-btn--ujarak'  => esc_html__( 'Ujarak', 'ultraaddons-elementor-lite' ),
                    'ua-btn--wayra'   => esc_html__( 'Wayra', 'ultraaddons-elementor-lite' ),
                    'ua-btn--tamaya'  => esc_html__( 'Tamaya', 'ultraaddons-elementor-lite' ),
                    'ua-btn--rayen'   => esc_html__( 'Rayen', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        // Icon Size
        $this->add_responsive_control(
            '_ua_btn_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'    => [ 'size' => 30, 'unit' => 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 500, 'step' => 1 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Typography Tabs
        $this->start_controls_tabs( '_ua_btn_typo_tabs' );

        $this->start_controls_tab( '_ua_btn_primary_typo', [
            'label' => esc_html__( 'Primary', 'ultraaddons-elementor-lite' ),
        ] );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_btn_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ],
                'selector' => '{{WRAPPER}} .ua-btn .cretive-button-text',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( '_ua_btn_secondary_typo', [
            'label' => esc_html__( 'Secondary', 'ultraaddons-elementor-lite' ),
        ] );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_ua_btn_secondary_typography',
                'global'   => [ 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ],
                'selector' => '{{WRAPPER}} .ua-btn--rayen::before, 
                               {{WRAPPER}} .ua-btn--winona::after, 
                               {{WRAPPER}} .ua-btn--tamaya .ua-btn--tamaya-secondary span',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Alignment
        $this->add_responsive_control(
            '_ua_btn_alignment',
            [
                'label'     => esc_html__( 'Button Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // Width
        $this->add_responsive_control(
            '_ua_btn_width',
            [
                'label'      => esc_html__( 'Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 500, 'step' => 1 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-btn' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            '_ua_btn_padding',
            [
                'label'      => esc_html__( 'Button Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--winona::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--winona > .creative-button-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--tamaya::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--rayen::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--rayen > .creative-button-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Gradient Toggle
        $this->add_control(
            '_ua_btn_use_gradient',
            [
                'label'        => esc_html__( 'Use Gradient Background', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        // ─── Normal / Hover Tabs ───
        $this->start_controls_tabs( '_ua_btn_style_tabs' );

        // ── Normal ──
        $this->start_controls_tab( '_ua_btn_normal', [
            'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
        ] );

        $this->add_control(
            '_ua_btn_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn i'                          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn .creative-button-inner svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_btn_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn'                                    => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn .ua-btn--tamaya-secondary'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--tamaya::before'             => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--tamaya::after'              => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_btn_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn'                                    => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--ujarak:hover'               => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--wayra:hover'                => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--tamaya::before'             => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--tamaya::after'              => 'background-color: {{VALUE}};',
                ],
                'condition' => [ '_ua_btn_use_gradient' => '' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_btn_gradient_bg',
                'types'    => [ 'gradient', 'classic' ],
                'selector' => '{{WRAPPER}} .ua-btn,
                               {{WRAPPER}} .ua-btn.ua-btn--ujarak:hover,
                               {{WRAPPER}} .ua-btn.ua-btn--wayra:hover,
                               {{WRAPPER}} .ua-btn.ua-btn--tamaya::before,
                               {{WRAPPER}} .ua-btn.ua-btn--tamaya::after',
                'condition' => [ '_ua_btn_use_gradient' => 'yes' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_ua_btn_border',
                'selector' => '{{WRAPPER}} .ua-btn',
            ]
        );

        $this->add_control(
            '_ua_btn_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [ 'px' => [ 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .ua-btn'         => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-btn::before'  => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .ua-btn::after'   => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        // ── Hover ──
        $this->start_controls_tab( '_ua_btn_hover', [
            'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
        ] );

        $this->add_control(
            '_ua_btn_hover_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn:hover i'                          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn:hover .creative-button-inner svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn:hover'                 => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--winona::after'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_ua_btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f54',
                'selectors' => [
                    '{{WRAPPER}} .ua-btn:hover'                                    => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--ujarak::before'                   => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--wayra:hover::before'              => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--tamaya:hover'                     => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--rayen::before'                    => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ua-btn.ua-btn--rayen:hover::before'              => 'background-color: {{VALUE}};',
                ],
                'condition' => [ '_ua_btn_use_gradient' => '' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_ua_btn_hover_gradient_bg',
                'types'    => [ 'gradient', 'classic' ],
                'selector' => '{{WRAPPER}} .ua-btn:hover,
                               {{WRAPPER}} .ua-btn.ua-btn--ujarak::before,
                               {{WRAPPER}} .ua-btn.ua-btn--wayra:hover::before,
                               {{WRAPPER}} .ua-btn.ua-btn--tamaya:hover,
                               {{WRAPPER}} .ua-btn.ua-btn--rayen::before,
                               {{WRAPPER}} .ua-btn.ua-btn--rayen:hover::before',
                'condition' => [ '_ua_btn_use_gradient' => 'yes' ],
            ]
        );

        $this->add_control(
            '_ua_btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Box Shadow (outside tabs)
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_ua_btn_box_shadow',
                'selector' => '{{WRAPPER}} .ua-btn',
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * STYLE TAB — Hover Animation (hover.css)
     *========================================================================*/
    protected function style_hover_animation_controls() {
        $this->start_controls_section(
            '_ua_btn_hover_anim_section',
            [
                'label' => esc_html__( 'Hover Animation', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_ua_btn_hover_animation',
            [
                'label'   => esc_html__( 'Hover Animation', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => ultraaddons_button_hover(),
                'default' => 'none',
                'description' => esc_html__( 'Additional hover.css animation effects (Sweep, Bounce, Shadow, Float etc.)', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->end_controls_section();
    }

    /*==========================================================================
     * RENDER
     *========================================================================*/
    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon_migrated = isset( $settings['__fa4_migrated']['_ua_btn_icon'] );
        $icon_is_new   = empty( $settings['_ua_btn_icon_old'] );

        // Build button classes
        $effect    = ! empty( $settings['_ua_btn_effect'] ) ? $settings['_ua_btn_effect'] : 'ua-btn--default';
        $icon_pos  = ! empty( $settings['_ua_btn_icon_position'] ) ? $settings['_ua_btn_icon_position'] : 'left';
        $hover_anim = ! empty( $settings['_ua_btn_hover_animation'] ) && $settings['_ua_btn_hover_animation'] !== 'none'
            ? $settings['_ua_btn_hover_animation']
            : '';

        $btn_classes = [ 'ua-btn', esc_attr( $effect ), 'ua-cb-icon-position-' . esc_attr( $icon_pos ) ];
        if ( $hover_anim ) {
            $btn_classes[] = esc_attr( $hover_anim );
        }

        $this->add_render_attribute( 'button', [
            'class' => $btn_classes,
        ] );

        if ( ! empty( $settings['_ua_btn_link']['url'] ) ) {
            $this->add_link_attributes( 'button', $settings['_ua_btn_link'] );
        }

        $secondary_text = ! empty( $settings['_ua_btn_secondary_text'] ) ? $settings['_ua_btn_secondary_text'] : '';
        $this->add_render_attribute( 'button', 'data-text', esc_attr( $secondary_text ) );

        $is_tamaya = ( $effect === 'ua-btn--tamaya' );
        $has_icon  = ! empty( $settings['_ua_btn_icon']['value'] );
        ?>
        <div class="ua-btn-wrap">
            <a <?php $this->print_render_attribute_string( 'button' ); ?>>

                <?php if ( $is_tamaya ) : ?>
                    <div class="ua-btn--tamaya-secondary ua-btn--tamaya-before">
                        <span><?php echo wp_kses_post( $secondary_text ); ?></span>
                    </div>
                <?php endif; ?>

                <div class="creative-button-inner">

                    <?php if ( ! $is_tamaya && $has_icon && $icon_pos === 'left' ) : ?>
                        <?php if ( $icon_migrated || $icon_is_new ) : ?>
                            <span class="ua-btn-icon ua-btn-icon-left">
                                <?php Icons_Manager::render_icon( $settings['_ua_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </span>
                        <?php elseif ( ! empty( $settings['_ua_btn_icon_old'] ) ) : ?>
                            <i class="<?php echo esc_attr( $settings['_ua_btn_icon_old'] ); ?> ua-btn-icon ua-btn-icon-left" aria-hidden="true"></i>
                        <?php endif; ?>
                    <?php endif; ?>

                    <span class="cretive-button-text"><?php echo esc_html( $settings['_ua_btn_text'] ); ?></span>

                    <?php if ( ! $is_tamaya && $has_icon && $icon_pos === 'right' ) : ?>
                        <?php if ( $icon_migrated || $icon_is_new ) : ?>
                            <span class="ua-btn-icon ua-btn-icon-right">
                                <?php Icons_Manager::render_icon( $settings['_ua_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </span>
                        <?php elseif ( ! empty( $settings['_ua_btn_icon_old'] ) ) : ?>
                            <i class="<?php echo esc_attr( $settings['_ua_btn_icon_old'] ); ?> ua-btn-icon ua-btn-icon-right" aria-hidden="true"></i>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>

                <?php if ( $is_tamaya ) : ?>
                    <div class="ua-btn--tamaya-secondary ua-btn--tamaya-after">
                        <span><?php echo wp_kses_post( $secondary_text ); ?></span>
                    </div>
                <?php endif; ?>

            </a>
        </div>
        <?php
    }
}
