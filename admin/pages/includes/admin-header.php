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
$full_logo_image    = ULTRA_ADDONS_ASSETS . 'images/ultraaddons-logo-new.png';
$full_logo_image    = apply_filters( 'ultraaddons/admin/header_logo', $full_logo_image );
?>
<div class="wrap about-wrap ultraaddons-wrap ultraaddons-admin-wrapper ua-version-<?php echo esc_attr( ultraaddons_plugin_version() ); ?>">
    <h1 class="ultraaddons-color-heading">
        <?php echo wp_kses_post( $header_title ); ?>
        <smal class="ultraaddons-version-in-title">v<?php echo esc_html( ULTRA_ADDONS_VERSION ); ?></smal>
    </h1>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
        <div class="ultraaddons-dashboard-area">
            <div class="ua-admin-header-wrapper">
                <div class="ua-branding">
                    <img src="<?php echo esc_attr( $full_logo_image ); ?>">
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

            <div class="ua-admin-welcome-content-area">
                <section class="welcome-banner" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/header-background.png' ); ?>);">
                    <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/transparent-logo.png' ); ?>" alt="" class="logo" />
                    <p class="greetings"><?php echo esc_html__( 'Thanks for choosing us!', 'ultraaddons' )?></p>
                </section>

                <section class="ua-section pr1">
                    <div class="info-box">
                        <div class="icon">
                            <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg-icon/file.svg' ); ?>" alt="" class="icon" />
                        </div>
                        <div class="info">
                            <h3><?php echo esc_html__( 'User Guidelines', 'ultraaddons' ); ?></h3>
                            <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                            <a href="#" class="ua-button button"><?php echo esc_html__( 'Documentation', 'ultraaddons' ); ?></a>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="icon">
                            <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg-icon/headphones.svg' ); ?>" alt="" class="icon" />
                        </div>
                        <div class="info">
                            <h3><?php echo esc_html__( 'Do you have any query?', 'ultraaddons' ); ?></h3>
                            <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                            <a href="#" class="ua-button button"><?php echo esc_html__( 'Contact Support', 'ultraaddons' ); ?></a>
                        </div>
                    </div>

                </section>

                <section class="ua-section pr2">
                    <div class="full-width info-box">
                        <h3 class="big"><?php echo esc_html__( 'Missing Any Features?', 'ultraaddons' ); ?></h3>
                        <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                        <a href="#" class="ua-button button"><?php echo esc_html__( 'Request For Features', 'ultraaddons' ); ?></a>
                    </div>
                </section>

            </div>
        
<?php 

do_action( 'ultraaddons/admin/after_admin_header' ); 

?>
