<?php
/**
 * This Template for Elementor Editor Page.
 * I mean: when Header Footer post will show on Single Page
 * 
 * we have used 
 * add_filter( 'template_include', 'ultraaddons_template_header_footer' );
 * 
 * @since 1.0.4.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<link rel="profile" href="https://gmpg.org/xfn/11">
        <style id="ultraaddons-core-header-footer-page">
            body.ultraaddons-header-footer-body{padding: 0;margin: 0;width: 100%;}
            .header-footer-fullwidth-page{width: 100%;}
            body.ultraaddons-header-footer-body div#page{display: block;margin: 0;padding: 0;}
        </style>
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'ultraaddons-header-footer-body' ); ?>>
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'ultraaddons' ); ?></a>
    <div id="page" class="hfeed site header-footer-fullwidth ultraaddons-page">
    <?php
        if ( have_posts() ) : while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        endif;

    wp_footer(); 
    ?>
    </div><!-- #page -->
</body>
</html>
