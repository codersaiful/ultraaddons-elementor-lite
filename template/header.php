<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Medilac
 */
$header_class = 'ultaaddons-elementor-header';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'medilac' ); ?></a>
    <?php
    
    /**
     * Insert Content or Do something at the Before Header of Site.
     * 
     * @HOOK for at Before Header
     */
    do_action( 'ultraaddons_before_header' );
    
    ?>
    <header id="masthead" class="site-header <?php echo esc_attr( $header_class ); ?>">
        
        <?php
        echo ultraaddons_elementor_display_content( UltraAddons\Core\Header_Footer::get_header_id() );
        ?>
    </header><!-- #masthead -->
    <?php
    
    /**
     * Insert Content or Do something at the Before Header of Site.
     * 
     * @HOOK for at Before Header
     */
    do_action( 'ultraaddons_after_header' );
    
    ?>
    <div id="page" class="hfeed site ultraaddons-container">
