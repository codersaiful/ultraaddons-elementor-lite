<?php
defined( 'ABSPATH' ) || die();

/**
 * Some Static Content as dynamic
 * 
 * and Set fitler, so that can Update from Addon plugin, if need
 * 
 * @version 1.0.0.11
 */
$header_title       = esc_html__( 'UltraAddons Elementor Lite', 'ultraaddons' );
$header_title       = apply_filters( 'ultraaddons/admin/header_title', $header_title );
$full_logo_image    = ULTRA_ADDONS_ASSETS . 'images/svg/full-color-logo.svg';
$full_logo_image    = apply_filters( 'ultraaddons/admin/header_logo', $full_logo_image );
?>
<div class="wrap about-wrap ultraaddons-wrap ultraaddons-admin-wrapper">
    <h1 class="ultraaddons-color-heading">
        <?php echo wp_kses_post( $header_title ); ?>
    </h1>
    
        <div class="ultraaddons-section-area">
        <img src="<?php echo esc_attr( $full_logo_image ); ?>" style="height: 176px;width: auto;">
<?php 

do_action( 'ultraaddons/admin/after_admin_header' ); 

?>
