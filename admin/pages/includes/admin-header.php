<?php
defined( 'ABSPATH' ) || die();

/**
 * Some Static Content as dynamic
 * 
 * and Set filter, so that can Update from Addon plugin, if need
 * 
 * @version 1.0.0.11
 */
$ultraaddons_header_title       = esc_html__( 'UltraAddons', 'ultraaddons-elementor-lite' );
$ultraaddons_header_title       = apply_filters( 'ultraaddons/admin/header_title', $ultraaddons_header_title );
$ultraaddons_full_logo_image    = ULTRA_ADDONS_ASSETS . 'images/ultraaddons-logo-new.png';
$ultraaddons_full_logo_image    = apply_filters( 'ultraaddons/admin/header_logo', $ultraaddons_full_logo_image );

$ultraaddons_get_page_name      = esc_html( get_admin_page_title() );
$ultraaddons_page_class         = strtolower(str_replace(' ', '-', $ultraaddons_get_page_name ));
$is_pro                         = function_exists( 'ultraaddons_is_pro' ) && ultraaddons_is_pro();
?>
<div class="wrap ultraaddons-wrap ultraaddons-admin-wrapper ua-modern-dashboard ua-version-<?php echo esc_attr( ultraaddons_plugin_version() ); ?>">
    <h1 class="wp-heading-inline screen-reader-text"><?php echo esc_html( $ultraaddons_get_page_name ); ?></h1>
    <hr class="wp-header-end">
    
    <div class="ultraaddons-dashboard-area <?php echo esc_html( $ultraaddons_page_class ); ?>">
        <!-- Modern SaaS Navbar -->
        <header class="ua-admin-top-navbar">
            <div class="ua-navbar-branding">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ultraaddons-elementor-lite' ) ); ?>" class="ua-brand-link">
                    <img src="<?php echo esc_url( $ultraaddons_full_logo_image ); ?>" alt="<?php esc_attr_e( 'UltraAddons', 'ultraaddons-elementor-lite' ); ?>" class="ua-brand-logo" />
                </a>
                <span class="ua-version-pill"><?php echo esc_html( ( $is_pro ? 'PRO ' : 'Lite ' ) . 'v' . ULTRA_ADDONS_VERSION ); ?></span>
            </div>

            <nav class="ua-navbar-nav">
                <ul class="ua-nav-menu">
                <?php
                $ultraaddons_current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
                $ultraaddons_sub_menus = UltraAddons\Admin\Admin_Handle::get_submenu_for_header();
                foreach( $ultraaddons_sub_menus as $sub_menu ){
                    if( ! isset( $sub_menu['position'] ) || ( isset( $sub_menu['menu_slug'] ) && $sub_menu['menu_slug'] === 'ultraaddons-help-n-others' ) ){
                        continue;
                    }
                    
                    $menu_title = $sub_menu['menu_title'];
                    $menu_slug  = $sub_menu['menu_slug'];
                    
                    // URL Resolution
                    if( strpos( $menu_slug, '.php' ) !== false ){
                        $link_url = admin_url( $menu_slug );
                    } else {
                        $link_url = admin_url( 'admin.php?page=' . $menu_slug );
                    }
                    
                    // Active state detection
                    $is_active = false;
                    if( $menu_slug === 'ultraaddons-elementor-lite' && ( $ultraaddons_current_page === 'ultraaddons-elementor-lite' || empty( $ultraaddons_current_page ) ) ){
                        $is_active = true;
                    } elseif( $menu_slug === $ultraaddons_current_page ){
                        $is_active = true;
                    } elseif( strpos( $menu_slug, 'post_type=' ) !== false && ! empty( $ultraaddons_post_type ) && strpos( $menu_slug, $ultraaddons_post_type ) !== false ){
                        $is_active = true;
                    } elseif( strpos( $menu_slug, 'taxonomy=' ) !== false && ! empty( $ultraaddons_taxonomy ) && strpos( $menu_slug, $ultraaddons_taxonomy ) !== false ){
                        $is_active = true;
                    }
                    
                    $active_class = $is_active ? 'ua-current-menu' : '';

                    // Menu icons
                    $icon_svg = '';
                    if( $menu_slug === 'ultraaddons-elementor-lite' ){
                        $icon_svg = '<svg class="ua-nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';
                    } elseif( $menu_slug === 'ultraaddons-widgets' ){
                        $icon_svg = '<svg class="ua-nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
                    } elseif( $menu_slug === 'ultraaddons-extensions' ){
                        $icon_svg = '<svg class="ua-nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>';
                    } elseif( strpos( $menu_slug, 'header_footer' ) !== false ){
                        $icon_svg = '<svg class="ua-nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>';
                    } elseif( strpos( $menu_slug, 'custom-fonts' ) !== false ){
                        $icon_svg = '<svg class="ua-nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 7 4 4 20 4 20 7"></polyline><line x1="9" y1="20" x2="15" y2="20"></line><line x1="12" y1="4" x2="12" y2="20"></line></svg>';
                    } elseif( $menu_slug === 'ultraaddons-elementor-settings' ){
                        $icon_svg = '<svg class="ua-nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06-.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>';
                    }
                ?>
                    <li class="ua-nav-item <?php echo esc_attr( $active_class . ' ' . $menu_slug ); ?>">
                        <a href="<?php echo esc_url( $link_url ); ?>" class="ua-nav-link">
                            <?php echo $icon_svg; ?>
                            <span><?php echo esc_html( $menu_title ); ?></span>
                        </a>
                    </li>
                <?php } ?>
                </ul>
            </nav>

            <div class="ua-navbar-actions">
                <a href="https://ultraaddons.com/widget/" target="_blank" class="ua-nav-action-btn ua-btn-docs" title="<?php esc_attr_e( 'Documentation', 'ultraaddons-elementor-lite' ); ?>">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <span><?php esc_html_e( 'Docs', 'ultraaddons-elementor-lite' ); ?></span>
                </a>
                <a href="https://codeastrology.com/my-support/" target="_blank" class="ua-nav-action-btn ua-btn-support" title="<?php esc_attr_e( 'Support', 'ultraaddons-elementor-lite' ); ?>">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <span><?php esc_html_e( 'Support', 'ultraaddons-elementor-lite' ); ?></span>
                </a>
                <?php if ( ! $is_pro ) : ?>
                    <a href="https://ultraaddons.com/pricing/" target="_blank" class="ua-nav-action-btn ua-btn-upgrade">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        <span><?php esc_html_e( 'Upgrade PRO', 'ultraaddons-elementor-lite' ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <div class="ua-main-body-content">
        
<?php 
do_action( 'ultraaddons/admin/after_admin_header' ); 
?>
