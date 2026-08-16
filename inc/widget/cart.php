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
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Cart extends Base {
    
    /**
     * Set your widget name keyword
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons-elementor-lite', 'ua', 'cart', 'wc', 'woocommerce', 'minicart', 'mini cart', 'offcanvas', 'side cart' ];
    }
    
    /**
     * Whether the reload preview is required or not.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool Whether the reload preview is required.
     */
    public function is_reload_preview_required() {
        return true;
    }
    
    /**
     * Register Control Handle from Here
     * 
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        // Content Tab Sections
        $this->content_general_controls();
        $this->content_item_elements_controls();
        $this->content_free_shipping_controls();
        $this->content_empty_cart_controls();
        
        // Style Tab Sections
        $this->content_general_style();
        $this->content_icon_style();
        $this->content_label_style();
        $this->content_header_style();
        $this->content_button_style();
        $this->content_offcanvas_style();
        $this->content_free_shipping_style();
    }
    
    /**
     * Helper to render preset SVG icons
     */
    protected function render_preset_icon( $select_icon, $settings ) {
        if ( 'custom' === $select_icon ) {
            $svg = ! empty( $settings['add_icon']['value']['url'] ) && is_string( $settings['add_icon']['value']['url'] ) ? $settings['add_icon']['value']['url'] : false;
            if ( $svg ) {
                echo '<img class="ua-cart-icon-image" src="' . esc_url( $svg ) . '" alt="' . esc_attr__( 'Cart Icon', 'ultraaddons-elementor-lite' ) . '">';
            } else {
                Icons_Manager::render_icon( $settings['add_icon'], [ 'aria-hidden' => 'true' ] );
            }
            return;
        }

        switch ( $select_icon ) {
            case 'bag-light':
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
                break;
            case 'bag-solid':
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm0 10c-2.76 0-5-2.24-5-5h2c0 1.66 1.34 3 3 3s3-1.34 3-3h2c0 2.76-2.24 5-5 5z"/></svg>';
                break;
            case 'cart-light':
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
                break;
            case 'cart-solid':
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>';
                break;
            case 'basket-light':
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 21 6 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>';
                break;
            case 'basket-solid':
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M17.21 9l-4.58-6.55c-.19-.28-.51-.45-.83-.45s-.64.17-.83.45L6.39 9H2v2h1.23l1.86 9.3c.16.82.88 1.41 1.72 1.41h10.38c.84 0 1.56-.59 1.72-1.41L20.77 11H22V9h-4.79zM9 9l3-4.29L15 9H9z"/></svg>';
                break;
            default:
                echo '<svg class="ua-preset-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
                break;
        }
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! class_exists( 'WooCommerce' ) ) {
            ?>
            <h3><?php echo esc_html__( 'WooCommerce is not activated.', 'ultraaddons-elementor-lite' ); ?></h3>
            <?php    
            return;
        }

        $select_icon       = ! empty( $settings['select_icon'] ) ? $settings['select_icon'] : 'bag-light';
        $toggle_prefix     = ! empty( $settings['toggle_prefix'] ) ? $settings['toggle_prefix'] : 'total_price';
        $cart_layout       = ! empty( $settings['cart_layout'] ) ? $settings['cart_layout'] : 'dropdown';
        $offcanvas_pos     = ! empty( $settings['offcanvas_position'] ) ? $settings['offcanvas_position'] : 'right';
        $trigger_type      = ! empty( $settings['trigger_type'] ) ? $settings['trigger_type'] : 'hover';
        $auto_open         = ! empty( $settings['auto_open_cart'] ) && 'yes' === $settings['auto_open_cart'];
        $icon_pos          = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'after';
        $sticky_cart       = ! empty( $settings['sticky_cart'] ) && 'yes' === $settings['sticky_cart'];
        $sticky_pos        = ! empty( $settings['sticky_position'] ) ? $settings['sticky_position'] : 'bottom-right';
        $custom_empty_cart = ! empty( $settings['custom_empty_cart'] ) && 'yes' === $settings['custom_empty_cart'];
        
        $title     = $settings['cart_title'];
        $cart_text = $settings['cart_label'];

        $wrapper_classes = [
            'ultraaddons-cart-wrapper',
            'ua-cart-layout-' . $cart_layout,
            'ua-icon-pos-' . $icon_pos,
            'ua-trigger-' . $trigger_type,
        ];
        if ( $sticky_cart ) {
            $wrapper_classes[] = 'ua-sticky-cart-enabled';
            $wrapper_classes[] = 'ua-sticky-pos-' . $sticky_pos;
        }

        $this->add_render_attribute( 'wrapper', 'class', implode( ' ', $wrapper_classes ) );
        $this->add_render_attribute( 'wrapper', 'data-layout', $cart_layout );
        $this->add_render_attribute( 'wrapper', 'data-trigger', $trigger_type );
        $this->add_render_attribute( 'wrapper', 'data-auto-open', $auto_open ? 'yes' : 'no' );
        $this->add_render_attribute( 'wrapper', 'data-ajax-url', admin_url( 'admin-ajax.php' ) );

        $this->add_render_attribute( 'cart_link', 'class', 'cart-link-li' );
        $this->add_render_attribute( 'cart_text', 'class', 'cart-text-li' );
        $this->add_render_attribute( 'cart', 'class', 'site-elementor-cart' );
        $this->add_render_attribute( 'cart', 'id', 'site-elementor-cart' );

        $this->add_render_attribute( 'icon-wrapper', 'class', 'icon-wrapper' );
        $this->add_render_attribute( 'icon-wrapper', 'href', wc_get_cart_url() );
        $this->add_render_attribute( 'icon-wrapper', 'title', __( 'View your shopping cart', 'ultraaddons-elementor-lite' ) );

        // Calculate Free Shipping Progress
        $show_shipping_bar = ! empty( $settings['enable_free_shipping_bar'] ) && 'yes' === $settings['enable_free_shipping_bar'];
        $min_amount        = ! empty( $settings['free_shipping_min_amount'] ) ? floatval( $settings['free_shipping_min_amount'] ) : 100;
        $current_subtotal  = WC()->cart ? floatval( WC()->cart->get_subtotal() ) : 0;
        $percent           = $min_amount > 0 ? min( 100, round( ( $current_subtotal / $min_amount ) * 100 ) ) : 100;
        $remaining         = max( 0, $min_amount - $current_subtotal );
        $formatted_remain  = wc_price( $remaining );

        $shipping_bar_html = '';
        if ( $show_shipping_bar ) {
            ob_start();
            ?>
            <div class="ua-free-shipping-bar-wrapper">
                <div class="ua-free-shipping-msg">
                    <?php 
                    if ( $remaining > 0 ) {
                        $msg_template = ! empty( $settings['free_shipping_text'] ) ? $settings['free_shipping_text'] : __( 'Add {amount} more to get FREE Shipping!', 'ultraaddons-elementor-lite' );
                        echo wp_kses_post( str_replace( '{amount}', '<span class="ua-free-shipping-amount">' . $formatted_remain . '</span>', $msg_template ) );
                    } else {
                        $success_msg = ! empty( $settings['free_shipping_success_text'] ) ? $settings['free_shipping_success_text'] : __( 'Congratulations! You unlocked FREE Shipping!', 'ultraaddons-elementor-lite' );
                        echo wp_kses_post( $success_msg );
                    }
                    ?>
                </div>
                <div class="ua-free-shipping-progress-track">
                    <div class="ua-free-shipping-progress-fill" style="width: <?php echo esc_attr( $percent ); ?>%;"></div>
                </div>
            </div>
            <?php
            $shipping_bar_html = ob_get_clean();
        }

        // Custom Empty Cart HTML
        $item_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        $empty_cart_html = '';
        if ( $custom_empty_cart && 0 === $item_count ) {
            $empty_title    = ! empty( $settings['empty_cart_title'] ) ? $settings['empty_cart_title'] : __( 'Your cart is empty', 'ultraaddons-elementor-lite' );
            $empty_desc     = ! empty( $settings['empty_cart_desc'] ) ? $settings['empty_cart_desc'] : '';
            $empty_btn_text = ! empty( $settings['empty_cart_btn_text'] ) ? $settings['empty_cart_btn_text'] : __( 'Start Shopping', 'ultraaddons-elementor-lite' );
            $empty_btn_url  = ! empty( $settings['empty_cart_btn_url']['url'] ) ? $settings['empty_cart_btn_url']['url'] : wc_get_page_permalink( 'shop' );

            ob_start();
            ?>
            <div class="ua-custom-empty-cart-wrapper">
                <div class="ua-empty-cart-icon">🛒</div>
                <h4 class="ua-empty-cart-title"><?php echo esc_html( $empty_title ); ?></h4>
                <?php if ( ! empty( $empty_desc ) ) : ?>
                    <p class="ua-empty-cart-desc"><?php echo esc_html( $empty_desc ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $empty_btn_text ) ) : ?>
                    <a href="<?php echo esc_url( $empty_btn_url ); ?>" class="ua-empty-cart-btn button"><?php echo esc_html( $empty_btn_text ); ?></a>
                <?php endif; ?>
            </div>
            <?php
            $empty_cart_html = ob_get_clean();
        }

        // Render Trigger Header Markup
        ob_start();
        ?>
        <div class="ua-cart-header-wrapper">
            <?php if ( 'before' === $icon_pos ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-wrapper ua-icon-badge-box" title="<?php esc_attr_e( 'View your shopping cart', 'ultraaddons-elementor-lite' ); ?>">
                    <span class="ua-cart-icon-container">
                        <?php $this->render_preset_icon( $select_icon, $settings ); ?>
                        <span class="ua-cart-badge-count"><?php echo esc_html( $item_count ); ?></span>
                    </span>
                </a>
            <?php endif; ?>

            <?php if ( 'icon_only' !== $icon_pos ) : ?>
                <?php if ( 'total_price' === $toggle_prefix && WC()->cart ) : ?>
                    <a class="ua-cart-text-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
                    </a>
                <?php elseif ( 'item_count' === $toggle_prefix ) : 
                    $item_count_text = _n( 'item', 'items', $item_count, 'ultraaddons-elementor-lite' );
                    ?>
                    <a class="ua-cart-text-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <span class="count">
                            <span class="cart-count"><?php echo esc_html( $item_count ); ?></span>
                            <span class="cart-item-text"><?php echo esc_html( $item_count_text ); ?></span>
                        </span>
                    </a>
                <?php elseif ( 'custom_text' === $toggle_prefix && ! empty( $cart_text ) ) : ?>
                    <a class="ua-cart-text-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <span class="cart-text-li"><?php echo esc_html( $cart_text ); ?></span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( 'after' === $icon_pos || 'icon_only' === $icon_pos ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-wrapper ua-icon-badge-box" title="<?php esc_attr_e( 'View your shopping cart', 'ultraaddons-elementor-lite' ); ?>">
                    <span class="ua-cart-icon-container">
                        <?php $this->render_preset_icon( $select_icon, $settings ); ?>
                        <span class="ua-cart-badge-count"><?php echo esc_html( $item_count ); ?></span>
                    </span>
                </a>
            <?php endif; ?>
        </div>
        <?php
        $trigger_link_html = ob_get_clean();
        ?>

        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( $sticky_cart ) : ?>
                <div class="ua-sticky-cart-trigger">
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-wrapper ua-icon-badge-box">
                        <span class="ua-cart-icon-container">
                            <?php $this->render_preset_icon( $select_icon, $settings ); ?>
                            <span class="ua-cart-badge-count"><?php echo esc_html( $item_count ); ?></span>
                        </span>
                    </a>
                </div>
            <?php endif; ?>

            <ul <?php echo $this->get_render_attribute_string( 'cart' ); ?>>
                <li <?php echo $this->get_render_attribute_string( 'cart_link' ); ?>>
                    <?php echo $trigger_link_html; ?>
                </li>

                <?php if ( 'dropdown' === $cart_layout ) : ?>
                    <li class="minicart-content-wrapper">
                        <?php
                        echo $shipping_bar_html;
                        do_action( 'ultraaddons_minicart_top' );
                        
                        if ( $custom_empty_cart && 0 === $item_count ) {
                            echo $empty_cart_html;
                        } else {
                            $instance = [ 'title' => $title ];
                            $instance = apply_filters( 'ultraaddons_minicart_args', $instance );
                            the_widget( 'WC_Widget_Cart', $instance );
                        }
                        
                        do_action( 'ultraaddons_minicart_bottom' );
                        ?>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ( 'offcanvas' === $cart_layout ) : ?>
                <div class="ua-cart-offcanvas-overlay"></div>
                <div class="ua-cart-offcanvas-drawer ua-offcanvas-pos-<?php echo esc_attr( $offcanvas_pos ); ?>">
                    <div class="ua-cart-offcanvas-header">
                        <h3 class="ua-cart-offcanvas-title"><?php echo esc_html( $title ? $title : __( 'Shopping Cart', 'ultraaddons-elementor-lite' ) ); ?></h3>
                        <button type="button" class="ua-cart-offcanvas-close" aria-label="Close">&times;</button>
                    </div>
                    <div class="ua-cart-offcanvas-body">
                        <?php
                        echo $shipping_bar_html;
                        do_action( 'ultraaddons_minicart_top' );
                        
                        if ( $custom_empty_cart && 0 === $item_count ) {
                            echo $empty_cart_html;
                        } else {
                            $instance = [ 'title' => '' ];
                            $instance = apply_filters( 'ultraaddons_minicart_args', $instance );
                            the_widget( 'WC_Widget_Cart', $instance );
                        }
                        
                        do_action( 'ultraaddons_minicart_bottom' );
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * General Section for Content Controls
     */
    protected function content_general_controls() {
        $this->start_controls_section(
                'general',
                [
                        'label' => __( 'General', 'ultraaddons-elementor-lite' ),
                ]
        );

        $this->add_control(
                'select_icon',
                [
                        'label' => __( 'Select Icon', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'bag-light',
                        'options' => [
                                'bag-light'    => __( 'Bag Light', 'ultraaddons-elementor-lite' ),
                                'bag-solid'    => __( 'Bag Solid', 'ultraaddons-elementor-lite' ),
                                'cart-light'   => __( 'Cart Light', 'ultraaddons-elementor-lite' ),
                                'cart-solid'   => __( 'Cart Solid', 'ultraaddons-elementor-lite' ),
                                'basket-light' => __( 'Basket Light', 'ultraaddons-elementor-lite' ),
                                'basket-solid' => __( 'Basket Solid', 'ultraaddons-elementor-lite' ),
                                'custom'       => __( 'Custom Icon / SVG', 'ultraaddons-elementor-lite' ),
                        ],
                ]
        );

        $this->add_control(
                'add_icon',
                [
                        'label' => __( 'Custom Cart Icon', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::ICONS,
                        'fa4compatibility' => 'icon',
                        'default' => [
                                'value' => 'fas fa-shopping-cart',
                                'library' => 'fa-solid',
                        ],
                        'condition' => [
                                'select_icon' => 'custom',
                        ],
                ]
        );

        $this->add_control(
                'toggle_prefix',
                [
                        'label' => __( 'Toggle Prefix', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'total_price',
                        'options' => [
                                'total_price' => __( 'Total Price', 'ultraaddons-elementor-lite' ),
                                'item_count'  => __( 'Item Count', 'ultraaddons-elementor-lite' ),
                                'custom_text' => __( 'Custom Text', 'ultraaddons-elementor-lite' ),
                                'none'        => __( 'None', 'ultraaddons-elementor-lite' ),
                        ],
                ]
        );

        $this->add_control(
                'cart_label',
                [
                        'label' => __( 'Custom Text Label', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => __( 'Shopping Cart', 'ultraaddons-elementor-lite' ),
                        'condition' => [
                                'toggle_prefix' => 'custom_text',
                        ],
                ]
        );

        $this->add_responsive_control(
                'align',
                [
                        'label' => __( 'Alignment', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                                'left'   => [
                                        'title' => __( 'Left', 'ultraaddons-elementor-lite' ),
                                        'icon'  => 'eicon-h-align-left',
                                ],
                                'center' => [
                                        'title' => __( 'Center', 'ultraaddons-elementor-lite' ),
                                        'icon'  => 'eicon-h-align-center',
                                ],
                                'right'  => [
                                        'title' => __( 'Right', 'ultraaddons-elementor-lite' ),
                                        'icon'  => 'eicon-h-align-right',
                                ]
                        ],
                        'prefix_class' => 'elementor%s-align-',
                        'default' => 'right',
                        'toggle' => false,
                ]
        );

        $this->add_control(
                'cart_layout',
                [
                        'label' => __( 'Cart Content', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'dropdown',
                        'options' => [
                                'dropdown'  => __( 'Dropdown', 'ultraaddons-elementor-lite' ),
                                'offcanvas' => __( 'Off-Canvas (Drawer)', 'ultraaddons-elementor-lite' ),
                                'none'      => __( 'None (Icon Only)', 'ultraaddons-elementor-lite' ),
                        ],
                ]
        );

        $this->add_control(
                'offcanvas_position',
                [
                        'label' => __( 'Off-Canvas Position', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'right',
                        'options' => [
                                'right' => __( 'Slide From Right', 'ultraaddons-elementor-lite' ),
                                'left'  => __( 'Slide From Left', 'ultraaddons-elementor-lite' ),
                        ],
                        'condition' => [
                                'cart_layout' => 'offcanvas',
                        ],
                ]
        );

        $this->add_control(
                'trigger_type',
                [
                        'label' => __( 'Trigger Action', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'hover',
                        'options' => [
                                'hover' => __( 'Hover', 'ultraaddons-elementor-lite' ),
                                'click' => __( 'Click', 'ultraaddons-elementor-lite' ),
                        ],
                        'condition' => [
                                'cart_layout!' => 'none',
                        ],
                ]
        );

        $this->add_control(
                'auto_open_cart',
                [
                        'label' => __( 'Auto Open on Add to Cart', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'No', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                        'condition' => [
                                'cart_layout!' => 'none',
                        ],
                ]
        );

        $this->add_control(
                'icon_position',
                [
                        'label' => __( 'Icon Position', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'after',
                        'options' => [
                                'after'     => __( 'After Text', 'ultraaddons-elementor-lite' ),
                                'before'    => __( 'Before Text', 'ultraaddons-elementor-lite' ),
                                'icon_only' => __( 'Icon Only (Badge Counter)', 'ultraaddons-elementor-lite' ),
                        ],
                ]
        );

        $this->add_control(
                'cart_title',
                [
                        'label' => __( 'Cart Title Header', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => __( 'Shopping Cart', 'ultraaddons-elementor-lite' ),
                ]
        );

        $this->add_control(
                'sticky_cart',
                [
                        'label' => __( 'Sticky Floating Cart Button', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'No', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => '',
                ]
        );

        $this->add_control(
                'sticky_position',
                [
                        'label' => __( 'Floating Button Position', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'bottom-right',
                        'options' => [
                                'bottom-right' => __( 'Bottom Right', 'ultraaddons-elementor-lite' ),
                                'bottom-left'  => __( 'Bottom Left', 'ultraaddons-elementor-lite' ),
                        ],
                        'condition' => [
                                'sticky_cart' => 'yes',
                        ],
                ]
        );

        $this->add_responsive_control(
                'item_box_width',
                [
                        'label' => __( 'Item Box Width', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 380,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 250,
                                        'max' => 800,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart > li.minicart-content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .ua-cart-offcanvas-drawer' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->end_controls_section();
    }

    protected function content_item_elements_controls() {
        $this->start_controls_section(
                'item_elements_section',
                [
                        'label' => __( 'Cart Content Elements', 'ultraaddons-elementor-lite' ),
                ]
        );

        $this->add_control(
                'show_product_image',
                [
                        'label' => __( 'Show Product Image', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                        'selectors_dictionary' => [
                                'yes' => 'display: inline-block !important;',
                                ''    => 'display: none !important;',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content ul.woocommerce-mini-cart li img' => '{{VALUE}}',
                        ],
                ]
        );

        $this->add_control(
                'show_product_price',
                [
                        'label' => __( 'Show Product Price & Quantity', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                        'selectors_dictionary' => [
                                'yes' => 'display: inline-flex !important;',
                                ''    => 'display: none !important;',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content ul.woocommerce-mini-cart li .quantity' => '{{VALUE}}',
                        ],
                ]
        );

        $this->add_control(
                'show_product_remove',
                [
                        'label' => __( 'Show Remove Icon (X)', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                        'selectors_dictionary' => [
                                'yes' => 'display: inline-flex !important;',
                                ''    => 'display: none !important;',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content ul.woocommerce-mini-cart li a.remove' => '{{VALUE}}',
                        ],
                ]
        );

        $this->end_controls_section();
    }

    protected function content_free_shipping_controls() {
        $this->start_controls_section(
                'free_shipping_section',
                [
                        'label' => __( 'Free Shipping Bar', 'ultraaddons-elementor-lite' ),
                ]
        );

        $this->add_control(
                'enable_free_shipping_bar',
                [
                        'label' => __( 'Enable Free Shipping Bar', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'No', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => '',
                ]
        );

        $this->add_control(
                'free_shipping_min_amount',
                [
                        'label' => __( 'Free Shipping Target Amount', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::NUMBER,
                        'default' => 100,
                        'condition' => [
                                'enable_free_shipping_bar' => 'yes',
                        ],
                ]
        );

        $this->add_control(
                'free_shipping_text',
                [
                        'label' => __( 'Remaining Message', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Add {amount} more to get FREE Shipping!', 'ultraaddons-elementor-lite' ),
                        'description' => __( 'Use {amount} placeholder for remaining price.', 'ultraaddons-elementor-lite' ),
                        'condition' => [
                                'enable_free_shipping_bar' => 'yes',
                        ],
                ]
        );

        $this->add_control(
                'free_shipping_success_text',
                [
                        'label' => __( 'Achieved Message', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Congratulations! You unlocked FREE Shipping!', 'ultraaddons-elementor-lite' ),
                        'condition' => [
                                'enable_free_shipping_bar' => 'yes',
                        ],
                ]
        );

        $this->end_controls_section();
    }

    protected function content_empty_cart_controls() {
        $this->start_controls_section(
                'empty_cart_section',
                [
                        'label' => __( 'Custom Empty Cart', 'ultraaddons-elementor-lite' ),
                ]
        );

        $this->add_control(
                'custom_empty_cart',
                [
                        'label' => __( 'Enable Custom Empty Cart Design', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Yes', 'ultraaddons-elementor-lite' ),
                        'label_off' => __( 'No', 'ultraaddons-elementor-lite' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                ]
        );

        $this->add_control(
                'empty_cart_title',
                [
                        'label' => __( 'Empty Title', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Your cart is empty', 'ultraaddons-elementor-lite' ),
                        'condition' => [
                                'custom_empty_cart' => 'yes',
                        ],
                ]
        );

        $this->add_control(
                'empty_cart_desc',
                [
                        'label' => __( 'Empty Description', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXTAREA,
                        'default' => __( 'Looks like you haven\'t added any items to your cart yet.', 'ultraaddons-elementor-lite' ),
                        'condition' => [
                                'custom_empty_cart' => 'yes',
                        ],
                ]
        );

        $this->add_control(
                'empty_cart_btn_text',
                [
                        'label' => __( 'Button Text', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Start Shopping', 'ultraaddons-elementor-lite' ),
                        'condition' => [
                                'custom_empty_cart' => 'yes',
                        ],
                ]
        );

        $this->add_control(
                'empty_cart_btn_url',
                [
                        'label' => __( 'Button Link', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::URL,
                        'placeholder' => __( 'https://your-shop.com/shop', 'ultraaddons-elementor-lite' ),
                        'default' => [
                                'url' => '',
                        ],
                        'condition' => [
                                'custom_empty_cart' => 'yes',
                        ],
                ]
        );

        $this->end_controls_section();
    }
    
    /**
     * General Style Section
     */
    protected function content_general_style(){
            $this->start_controls_section(
                    'general_style',
                    [
                            'label' => __( 'General', 'ultraaddons-elementor-lite' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_control(
                    'wrapper_bg_color',
                    [
                            'label'     => __( 'Background', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'wrapper_padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'top' => 5,
                                    'bottom' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper ul.site-elementor-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->end_controls_section();
    }
    
    /**
     * Cart Icon Style Section
     */
    protected function content_icon_style(){
            $this->start_controls_section(
                    'icon_style',
                    [
                            'label' => __( 'Cart Icon', 'ultraaddons-elementor-lite' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_responsive_control(
                    'icon_size',
                    [
                            'label' => __( 'Size', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', 'em', 'rem' ],
                            'range' => [
                                    'px' => [
                                            'min' => 10,
                                            'max' => 100,
                                    ],
                            ],
                            'default' => [
                                    'size' => 20,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                                    '{{WRAPPER}} .ua-sticky-cart-trigger .icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .ua-sticky-cart-trigger .icon-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                            ],
                    ]
            );
            
            $this->add_control(
                    'icon_color',
                    [
                            'label'     => __( 'Color', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper i' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper svg' => 'fill: {{VALUE}}; stroke: {{VALUE}}; color: {{VALUE}};',
                                    '{{WRAPPER}} .ua-sticky-cart-trigger .icon-wrapper i' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'icon_bg_color',
                    [
                            'label'     => __( 'Background', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => 'transparent',
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper' => 'background-color: {{VALUE}}',
                                    '{{WRAPPER}} .ua-sticky-cart-trigger' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_responsive_control(
                    'icon-padding',
                    [
                            'label' => __( 'Padding', 'ultraaddons-elementor-lite' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'default'   => [
                                    'top' => 5,
                                    'bottom' => 5,
                                    'unit' => 'px',
                            ],
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .site-elementor-cart li.cart-link-li .icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                    ]
            );
            
            $this->end_controls_section();
    }
    
    /**
     * Label Section for Mini Cart
     */
    protected function content_label_style(){
            $this->start_controls_section(
                    'label_style',
                    [
                            'label' => __( 'Label & Price', 'ultraaddons-elementor-lite' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_control(
                    'label_color',
                    [
                            'label'     => __( 'Text / Label Color', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .ua-cart-text-link' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .ua-cart-text-link span' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'label_typography',
                            'label' => 'Label Typography',
                            'selector' => '{{WRAPPER}} .ultraaddons-cart-wrapper .ua-cart-text-link',
                    ]
            );
            
            $this->add_control(
                    'price_color',
                    [
                            'label'     => __( 'Price Color', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .ua-cart-text-link span.amount' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .ua-cart-text-link .woocommerce-Price-amount' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'price_bg_color',
                    [
                            'label'     => __( 'Price Background Color', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ultraaddons-cart-wrapper .ua-cart-text-link span.amount' => 'background-color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_control(
                    'qty_color',
                    [
                            'label'     => __( 'Quantity Badge Color', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ua-cart-badge-count' => 'color: {{VALUE}} !important',
                            ],
                    ]
            );
            
            $this->add_control(
                    'qty_bg_color',
                    [
                            'label'     => __( 'Quantity Badge Background', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .ua-cart-badge-count' => 'background-color: {{VALUE}} !important',
                            ],
                    ]
            );
            
            $this->end_controls_section();
    }
    
    /**
     * Header Style Section
     */
    protected function content_header_style(){
            $this->start_controls_section(
                    'header_style',
                    [
                            'label' => __( 'Header Title', 'ultraaddons-elementor-lite' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            $this->add_control(
                    'title_color',
                    [
                            'label'     => __( 'Color', 'ultraaddons-elementor-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .widget_shopping_cart h2.widgettitle' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .ua-cart-offcanvas-title' => 'color: {{VALUE}}',
                            ],
                    ]
            );
            
            $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'title_typography',
                            'label' => 'Typography',
                            'selector' => '{{WRAPPER}} .widget_shopping_cart h2.widgettitle, {{WRAPPER}} .ua-cart-offcanvas-title',
                    ]
            );
            
            $this->end_controls_section();
    }

    protected function content_button_style() {
        $this->start_controls_section(
                'button_style_section',
                [
                        'label' => __( 'View Cart & Checkout Buttons', 'ultraaddons-elementor-lite' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'button_layout',
                [
                        'label' => __( 'Button Layout', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'stacked',
                        'options' => [
                                'stacked' => __( 'Stacked (Vertical)', 'ultraaddons-elementor-lite' ),
                                'inline'  => __( 'Inline (Side by Side)', 'ultraaddons-elementor-lite' ),
                        ],
                        'selectors_dictionary' => [
                                'stacked' => 'display: flex; flex-direction: column; gap: 0;',
                                'inline'  => 'display: flex; flex-direction: row; gap: 10px;',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons.buttons' => '{{VALUE}}',
                        ],
                ]
        );

        $this->add_control(
                'view_cart_heading',
                [
                        'label' => __( 'View Cart Button', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_control(
                'view_cart_color',
                [
                        'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a:not(.checkout)' => 'color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'view_cart_bg',
                [
                        'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a:not(.checkout)' => 'background-color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'view_cart_hover_color',
                [
                        'label' => __( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a:not(.checkout):hover' => 'color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'view_cart_hover_bg',
                [
                        'label' => __( 'Hover Background', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a:not(.checkout):hover' => 'background-color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'checkout_heading',
                [
                        'label' => __( 'Checkout Button', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                ]
        );

        $this->add_control(
                'checkout_color',
                [
                        'label' => __( 'Text Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a.checkout' => 'color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'checkout_bg',
                [
                        'label' => __( 'Background Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a.checkout' => 'background-color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'checkout_hover_color',
                [
                        'label' => __( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a.checkout:hover' => 'color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->add_control(
                'checkout_hover_bg',
                [
                        'label' => __( 'Hover Background', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .widget_shopping_cart_content .woocommerce-mini-cart__buttons a.checkout:hover' => 'background-color: {{VALUE}} !important;',
                        ],
                ]
        );

        $this->end_controls_section();
    }

    protected function content_offcanvas_style() {
        $this->start_controls_section(
                'offcanvas_style_section',
                [
                        'label' => __( 'Off-Canvas Side Drawer', 'ultraaddons-elementor-lite' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                        'condition' => [
                                'cart_layout' => 'offcanvas',
                        ],
                ]
        );

        $this->add_control(
                'offcanvas_bg',
                [
                        'label' => __( 'Drawer Background', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-cart-offcanvas-drawer' => 'background-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_control(
                'overlay_bg',
                [
                        'label' => __( 'Overlay Backdrop Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-cart-offcanvas-overlay' => 'background-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_control(
                'offcanvas_close_color',
                [
                        'label' => __( 'Close Icon (X) Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-cart-offcanvas-close' => 'color: {{VALUE}};',
                        ],
                ]
        );

        $this->end_controls_section();
    }

    protected function content_free_shipping_style() {
        $this->start_controls_section(
                'free_shipping_style_section',
                [
                        'label' => __( 'Free Shipping Bar Style', 'ultraaddons-elementor-lite' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                        'condition' => [
                                'enable_free_shipping_bar' => 'yes',
                        ],
                ]
        );

        $this->add_control(
                'free_shipping_bar_fill_color',
                [
                        'label' => __( 'Progress Bar Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-free-shipping-progress-fill' => 'background-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_control(
                'free_shipping_bar_bg_color',
                [
                        'label' => __( 'Bar Background Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-free-shipping-progress-track' => 'background-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_control(
                'free_shipping_msg_color',
                [
                        'label' => __( 'Message Text Color', 'ultraaddons-elementor-lite' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                                '{{WRAPPER}} .ua-free-shipping-msg' => 'color: {{VALUE}};',
                        ],
                ]
        );

        $this->end_controls_section();
    }
}