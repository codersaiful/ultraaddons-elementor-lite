<?php
defined( 'ABSPATH' ) || die();

/**
 * Some Static Content as dynamic
 * 
 * and Set fitler, so that can Update from Addon plugin, if need
 * 
 * @version 1.0.0.11
 */
$ultraaddons_header_title       = esc_html__( 'UltraAddons', 'ultraaddons-elementor-lite' );
$ultraaddons_header_title       = apply_filters( 'ultraaddons/admin/header_title', $ultraaddons_header_title );
$ultraaddons_full_logo_image    = ULTRA_ADDONS_ASSETS . 'images/ultraaddons-logo-new.png';
$ultraaddons_full_logo_image    = apply_filters( 'ultraaddons/admin/header_logo', $ultraaddons_full_logo_image );
/**
 * @Modify by BM Rafiul Alam
 * Add unique CSS class name based on admin pages
 */
$ultraaddons_get_page_name      = esc_html( get_admin_page_title() );
$ultraaddons_page_class         = strtolower(str_replace(' ', '-', $ultraaddons_get_page_name ));
?>
<div class="wrap about-wrap ultraaddons-wrap ultraaddons-admin-wrapper ua-version-<?php echo esc_attr( ultraaddons_plugin_version() ); ?>">
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <div class="ultraaddons-dashboard-area <?php echo esc_html( $ultraaddons_page_class ); ?>">
            <div class="ua-admin-header-wrapper">
                <div class="ua-branding">
                    <img src="<?php echo esc_attr( $ultraaddons_full_logo_image ); ?>">
                    <div class="ultraaddons-version-in-title">Version <strong><?php echo esc_html( ULTRA_ADDONS_VERSION ); ?></strong></div>
                </div>
                <!-- <h1 class="ultraaddons-color-heading">
                    <?php //echo wp_kses_post( $ultraaddons_header_title ); ?>
                    <smal class="ultraaddons-version-in-title">v<?php //echo esc_html( ULTRA_ADDONS_VERSION ); ?></smal>
                </h1> -->
                <div class="ua-header-menu">
                    <ul class="ua-submenu">
                    <?php
                    $ultraaddons_sub_menus = UltraAddons\Admin\Admin_Handle::get_submenu_for_header();
                    //There is no nonce verification needed as we are just reading the 'page' parameter
                    //phpcs:ignore WordPress.Security.NonceVerification.Recommended 
                    $ultraaddons_current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
                    foreach( $ultraaddons_sub_menus as $ultraaddons_sub_menu ){
                        if( ! isset( $ultraaddons_sub_menu['position'] ) || $ultraaddons_sub_menu['menu_slug'] == 'ultraaddons-help-n-others' ){
                            continue;
                        }
                        
                        
                        $ultraaddons_menu_title = $ultraaddons_sub_menu['menu_title'];
                        $ultraaddons_menu_slug = $ultraaddons_sub_menu['menu_slug'];
                        $ultraaddons_active_class = $ultraaddons_current_page == $ultraaddons_menu_slug ? 'ua-current-menu' : '';
                    ?>
                        <li class="<?php echo esc_attr( $ultraaddons_active_class . ' ' . $ultraaddons_menu_slug ); ?>">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $ultraaddons_menu_slug ) ); ?>">
                                <?php echo esc_html( $ultraaddons_menu_title ); ?>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                    </ul>
                </div>
            </div>

        
<?php 

do_action( 'ultraaddons/admin/after_admin_header' ); 

?>
