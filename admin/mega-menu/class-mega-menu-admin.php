<?php
namespace UltraAddons\Admin;

defined( 'ABSPATH' ) || die();

use UltraAddons\Core\Mega_Menu;

/**
 * UltraAddons Mega Menu Admin UI
 *
 * Integrates the Mega Menu configuration modal into WordPress Appearance > Menus (nav-menus.php).
 *
 * @package UltraAddons
 * @since 3.1.0
 */
class Mega_Menu_Admin {

    /**
     * Initialize admin hooks
     */
    public static function init() {
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
        add_action( 'admin_footer', [ __CLASS__, 'render_settings_modal' ] );
    }

    /**
     * Enqueue CSS and JS assets on Appearance > Menus
     *
     * @param string $hook_suffix
     */
    public static function enqueue_admin_assets( $hook_suffix ) {
        if ( 'nav-menus.php' !== $hook_suffix ) {
            return;
        }

        $css_file = ULTRA_ADDONS_DIR . 'admin/mega-menu/css/mega-menu-admin.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_enqueue_style(
            'ua-mega-menu-admin',
            ULTRA_ADDONS_URL . 'admin/mega-menu/css/mega-menu-admin.css',
            [ 'wp-color-picker' ],
            $css_ver
        );

        $js_file = ULTRA_ADDONS_DIR . 'admin/mega-menu/js/mega-menu-admin.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_enqueue_script(
            'ua-mega-menu-admin',
            ULTRA_ADDONS_URL . 'admin/mega-menu/js/mega-menu-admin.js',
            [ 'jquery', 'wp-color-picker' ],
            $js_ver,
            true
        );

        // Preload settings for all menu items currently in the active menu
        $menu_items_settings = [];
        $nav_menus = wp_get_nav_menus();
        if ( ! empty( $nav_menus ) ) {
            // Get current menu or first menu
            $nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;
            if ( ! $nav_menu_selected_id ) {
                $recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
                $nav_menu_selected_id = $recently_edited ?: ( $nav_menus[0]->term_id ?? 0 );
            }

            if ( $nav_menu_selected_id ) {
                $items = wp_get_nav_menu_items( $nav_menu_selected_id );
                if ( ! empty( $items ) ) {
                    foreach ( $items as $item ) {
                        $menu_items_settings[ $item->ID ] = [
                            'settings'    => Mega_Menu::get_settings( $item->ID ),
                            'has_mega'    => Mega_Menu::has_mega_menu( $item->ID ),
                            'template_id' => Mega_Menu::get_template_id( $item->ID ),
                        ];
                    }
                }
            }
        }

        wp_localize_script(
            'ua-mega-menu-admin',
            'uaMegaMenuAdmin',
            [
                'ajax_url'    => admin_url( 'admin-ajax.php' ),
                'security'    => wp_create_nonce( 'ua_mega_menu_admin_nonce' ),
                'items_data'  => $menu_items_settings,
                'i18n'        => [
                    'btn_text'       => esc_html__( 'Ultra Mega Menu', 'ultraaddons-elementor-lite' ),
                    'btn_enabled'    => esc_html__( 'Mega Menu Active', 'ultraaddons-elementor-lite' ),
                    'saving'         => esc_html__( 'Saving...', 'ultraaddons-elementor-lite' ),
                    'saved'          => esc_html__( 'Saved Successfully!', 'ultraaddons-elementor-lite' ),
                    'creating'       => esc_html__( 'Preparing Elementor Canvas...', 'ultraaddons-elementor-lite' ),
                    'error'          => esc_html__( 'An error occurred. Please try again.', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );
    }

    /**
     * Render modern settings modal and Elementor Iframe editor overlay in Appearance > Menus footer
     */
    public static function render_settings_modal() {
        $screen = get_current_screen();
        if ( ! $screen || 'nav-menus' !== $screen->base ) {
            return;
        }
        ?>
        <!-- UltraAddons Mega Menu Modal Dialog -->
        <div id="ua-mega-modal-overlay" class="ua-mega-modal-overlay" style="display: none;">
            <div class="ua-mega-modal-dialog">
                
                <!-- Modal Header -->
                <div class="ua-mega-modal-header">
                    <div class="ua-mega-header-brand">
                        <span class="ua-mega-badge-logo">UA</span>
                        <div class="ua-mega-header-text">
                            <h3><?php esc_html_e( 'Ultra Mega Menu Settings', 'ultraaddons-elementor-lite' ); ?></h3>
                            <p class="ua-mega-item-name"><?php esc_html_e( 'Menu Item: ', 'ultraaddons-elementor-lite' ); ?><strong id="ua-modal-item-title">-</strong></p>
                        </div>
                    </div>
                    <button type="button" class="ua-mega-modal-close" aria-label="<?php esc_attr_e( 'Close', 'ultraaddons-elementor-lite' ); ?>">&times;</button>
                </div>

                <!-- Modal Tabs -->
                <div class="ua-mega-modal-tabs">
                    <button type="button" class="ua-mega-tab-btn ua-tab-active" data-tab="general">
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php esc_html_e( 'General & Layout', 'ultraaddons-elementor-lite' ); ?>
                    </button>
                    <button type="button" class="ua-mega-tab-btn" data-tab="icon">
                        <span class="dashicons dashicons-art"></span>
                        <?php esc_html_e( 'Menu Icon', 'ultraaddons-elementor-lite' ); ?>
                    </button>
                    <button type="button" class="ua-mega-tab-btn" data-tab="badge">
                        <span class="dashicons dashicons-tag"></span>
                        <?php esc_html_e( 'Badge / Label', 'ultraaddons-elementor-lite' ); ?>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="ua-mega-modal-body">
                    
                    <!-- TAB 1: General & Layout -->
                    <div class="ua-mega-tab-content ua-tab-active" id="ua-tab-general">
                        
                        <div class="ua-field-row ua-toggle-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-enable"><strong><?php esc_html_e( 'Enable Mega Menu', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                <span class="ua-field-desc"><?php esc_html_e( 'Transform this item into an Elementor-powered mega menu dropdown.', 'ultraaddons-elementor-lite' ); ?></span>
                            </div>
                            <div class="ua-field-control">
                                <label class="ua-switch">
                                    <input type="checkbox" id="ua-setting-enable" value="1">
                                    <span class="ua-slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="ua-mega-dependent-options">
                            
                            <div class="ua-field-row ua-highlight-box">
                                <div class="ua-field-label">
                                    <label><strong><?php esc_html_e( 'Mega Menu Content', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                    <span class="ua-field-desc"><?php esc_html_e( 'Design the dropdown visually with Elementor nested containers and widgets.', 'ultraaddons-elementor-lite' ); ?></span>
                                </div>
                                <div class="ua-field-control">
                                    <button type="button" id="ua-btn-edit-elementor" class="button button-primary ua-btn-elementor">
                                        <span class="dashicons dashicons-edit"></span>
                                        <?php esc_html_e( 'Edit Content with Elementor', 'ultraaddons-elementor-lite' ); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="ua-field-row">
                                <div class="ua-field-label">
                                    <label for="ua-setting-width-type"><strong><?php esc_html_e( 'Dropdown Width Type', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                    <span class="ua-field-desc"><?php esc_html_e( 'Choose how wide the mega dropdown panel should stretch.', 'ultraaddons-elementor-lite' ); ?></span>
                                </div>
                                <div class="ua-field-control">
                                    <select id="ua-setting-width-type" class="ua-select">
                                        <option value="container"><?php esc_html_e( 'Container Width (Header Box)', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="full"><?php esc_html_e( 'Full Width (100% Screen Viewport)', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="custom"><?php esc_html_e( 'Custom Pixel Width (px)', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="fit"><?php esc_html_e( 'Fit to Inner Content', 'ultraaddons-elementor-lite' ); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="ua-field-row ua-custom-width-row" style="display: none;">
                                <div class="ua-field-label">
                                    <label for="ua-setting-custom-width"><strong><?php esc_html_e( 'Custom Width (px)', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                </div>
                                <div class="ua-field-control">
                                    <input type="number" id="ua-setting-custom-width" value="760" min="280" max="1920" step="10" class="ua-input-number">
                                    <span class="ua-unit">px</span>
                                </div>
                            </div>

                            <div class="ua-field-row">
                                <div class="ua-field-label">
                                    <label for="ua-setting-position"><strong><?php esc_html_e( 'Dropdown Alignment', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                    <span class="ua-field-desc"><?php esc_html_e( 'Horizontal alignment relative to parent item or container.', 'ultraaddons-elementor-lite' ); ?></span>
                                </div>
                                <div class="ua-field-control">
                                    <select id="ua-setting-position" class="ua-select">
                                        <option value="center"><?php esc_html_e( 'Center Aligned', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="left"><?php esc_html_e( 'Left Aligned to Item', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="right"><?php esc_html_e( 'Right Aligned to Item', 'ultraaddons-elementor-lite' ); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="ua-field-row">
                                <div class="ua-field-label">
                                    <label for="ua-setting-mobile-mode"><strong><?php esc_html_e( 'Mobile Submenu Mode', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                    <span class="ua-field-desc"><?php esc_html_e( 'Determine how this mega menu renders on mobile touchscreens.', 'ultraaddons-elementor-lite' ); ?></span>
                                </div>
                                <div class="ua-field-control">
                                    <select id="ua-setting-mobile-mode" class="ua-select">
                                        <option value="mega"><?php esc_html_e( 'Render Mega Menu on Mobile', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="wp_sub"><?php esc_html_e( 'Show Standard WordPress Sub-items (Lightweight)', 'ultraaddons-elementor-lite' ); ?></option>
                                        <option value="ajax"><?php esc_html_e( 'Load with AJAX on Tap (Speed Optimized)', 'ultraaddons-elementor-lite' ); ?></option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- TAB 2: Menu Icon -->
                    <div class="ua-mega-tab-content" id="ua-tab-icon" style="display: none;">
                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-icon"><strong><?php esc_html_e( 'Icon Class', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                <span class="ua-field-desc"><?php esc_html_e( 'Enter icon class, e.g. "fas fa-star" or "dashicons dashicons-star-filled".', 'ultraaddons-elementor-lite' ); ?></span>
                            </div>
                            <div class="ua-field-control">
                                <input type="text" id="ua-setting-icon" placeholder="fas fa-fire" class="ua-input-text">
                            </div>
                        </div>

                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-icon-color"><strong><?php esc_html_e( 'Icon Color', 'ultraaddons-elementor-lite' ); ?></strong></label>
                            </div>
                            <div class="ua-field-control">
                                <input type="text" id="ua-setting-icon-color" value="#4f46e5" class="ua-color-field">
                            </div>
                        </div>

                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-icon-size"><strong><?php esc_html_e( 'Icon Size (px)', 'ultraaddons-elementor-lite' ); ?></strong></label>
                            </div>
                            <div class="ua-field-control">
                                <input type="number" id="ua-setting-icon-size" value="16" min="10" max="48" class="ua-input-number">
                                <span class="ua-unit">px</span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Badge / Label -->
                    <div class="ua-mega-tab-content" id="ua-tab-badge" style="display: none;">
                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-badge-text"><strong><?php esc_html_e( 'Badge Text', 'ultraaddons-elementor-lite' ); ?></strong></label>
                                <span class="ua-field-desc"><?php esc_html_e( 'Short highlight text e.g. "HOT", "NEW", "50% OFF".', 'ultraaddons-elementor-lite' ); ?></span>
                            </div>
                            <div class="ua-field-control">
                                <input type="text" id="ua-setting-badge-text" placeholder="HOT" maxlength="20" class="ua-input-text">
                            </div>
                        </div>

                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-badge-style"><strong><?php esc_html_e( 'Badge Style', 'ultraaddons-elementor-lite' ); ?></strong></label>
                            </div>
                            <div class="ua-field-control">
                                <select id="ua-setting-badge-style" class="ua-select">
                                    <option value="pill"><?php esc_html_e( 'Solid Capsule (Pill)', 'ultraaddons-elementor-lite' ); ?></option>
                                    <option value="outline"><?php esc_html_e( 'Outline Border', 'ultraaddons-elementor-lite' ); ?></option>
                                    <option value="soft"><?php esc_html_e( 'Soft Tint Background', 'ultraaddons-elementor-lite' ); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-badge-bg"><strong><?php esc_html_e( 'Badge Background Color', 'ultraaddons-elementor-lite' ); ?></strong></label>
                            </div>
                            <div class="ua-field-control">
                                <input type="text" id="ua-setting-badge-bg" value="#4f46e5" class="ua-color-field">
                            </div>
                        </div>

                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-badge-color"><strong><?php esc_html_e( 'Badge Text Color', 'ultraaddons-elementor-lite' ); ?></strong></label>
                            </div>
                            <div class="ua-field-control">
                                <input type="text" id="ua-setting-badge-color" value="#ffffff" class="ua-color-field">
                            </div>
                        </div>

                        <div class="ua-field-row">
                            <div class="ua-field-label">
                                <label for="ua-setting-badge-animation"><strong><?php esc_html_e( 'Badge Animation', 'ultraaddons-elementor-lite' ); ?></strong></label>
                            </div>
                            <div class="ua-field-control">
                                <select id="ua-setting-badge-animation" class="ua-select">
                                    <option value="none"><?php esc_html_e( 'None (Static)', 'ultraaddons-elementor-lite' ); ?></option>
                                    <option value="pulse-dot"><?php esc_html_e( 'Pulsing Glowing Dot (Live Attention)', 'ultraaddons-elementor-lite' ); ?></option>
                                    <option value="shimmer"><?php esc_html_e( 'Shimmering Light Sweep', 'ultraaddons-elementor-lite' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="ua-mega-modal-footer">
                    <span class="ua-status-msg" style="display: none;"></span>
                    <button type="button" class="button ua-modal-btn-cancel"><?php esc_html_e( 'Cancel', 'ultraaddons-elementor-lite' ); ?></button>
                    <button type="button" id="ua-btn-save-settings" class="button button-primary ua-modal-btn-save"><?php esc_html_e( 'Save Mega Menu', 'ultraaddons-elementor-lite' ); ?></button>
                </div>

            </div>
        </div>

        <!-- Fullscreen Elementor Editor Iframe Overlay -->
        <div id="ua-mega-editor-overlay" class="ua-mega-editor-overlay" style="display: none;">
            <div class="ua-mega-editor-header">
                <span class="ua-mega-badge-logo">UA</span>
                <span class="ua-editor-title"><?php esc_html_e( 'UltraAddons Mega Menu Canvas Editor', 'ultraaddons-elementor-lite' ); ?></span>
                <button type="button" class="ua-editor-close-btn" title="<?php esc_attr_e( 'Close Editor', 'ultraaddons-elementor-lite' ); ?>">&times;</button>
            </div>
            <div class="ua-mega-editor-frame-container">
                <div class="ua-editor-loading-spinner">
                    <div class="ua-spinner"></div>
                    <p><?php esc_html_e( 'Loading Elementor Canvas...', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
                <iframe id="ua-mega-editor-iframe" src="" frameborder="0"></iframe>
            </div>
        </div>
        <?php
    }
}
