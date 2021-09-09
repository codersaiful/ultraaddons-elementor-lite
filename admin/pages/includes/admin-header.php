<?php
defined( 'ABSPATH' ) || die();

/**
 * Some Static Content as dynamic
 * 
 * and Set fitler, so that can Update from Addon plugin, if need
 * 
 * @version 1.0.0.11
 */
$header_title       = esc_html__( 'UltraAddons', 'ultraaddons' );
$header_title       = apply_filters( 'ultraaddons/admin/header_title', $header_title );
$full_logo_image    = ULTRA_ADDONS_ASSETS . 'images/svg/full-color-logo.svg';
$full_logo_image    = apply_filters( 'ultraaddons/admin/header_logo', $full_logo_image );
?>
<div class="wrap about-wrap ultraaddons-wrap ultraaddons-admin-wrapper ua-version-<?php echo esc_attr( ultraaddons_plugin_version() ); ?>">
    <h1 class="ultraaddons-color-heading">
        <?php echo wp_kses_post( $header_title ); ?>
        <smal class="ultraaddons-version-in-title">v<?php echo esc_html( ULTRA_ADDONS_VERSION ); ?></smal>
    </h1>
    
        <div class="ultraaddons-dashboard-area">
            <div class="ua-admin-header-wrapper">
                <div class="ua-branding">
                    <img src="<?php echo esc_attr( $full_logo_image ); ?>" style="height: 176px;width: auto;">
                </div>
                <div class="ua-header-menu">
                    <ul class="ua-submenu">
                    <?php
                    $sub_menus = UltraAddons\Admin\Admin_Handle::get_submenu_for_header();

                    $current_page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : false;
                    foreach( $sub_menus as $sub_menu ){
                        if( $sub_menu['menu_slug'] == 'ultraaddons-help-n-others' ){
                            continue;
                        }
                        
                        
                        $menu_title = $sub_menu['menu_title'];
                        $menu_slug = $sub_menu['menu_slug'];
                        $active_class = $current_page == $menu_slug ? 'ua-current-menu' : '';
                    ?>
                        <li class="<?php echo esc_attr( $active_class . ' ' . $menu_slug ); ?>">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $menu_slug ) ); ?>">
                                <?php echo esc_html( $menu_title ); ?>
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
