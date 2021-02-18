<?php

/**
 * Custom Header file
 * by UltraAddons
 * 
 * @package UltraAddons
 * @category Core
 * 
 * @since 1.0.0.10
 */

?>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php
$_ultraaddons_classes = apply_filters( 'ultraaddons_body_class', $_ultraaddons_classes );
body_class( $_ultraaddons_classes ); 
?>>
<?php wp_body_open(); ?>
    
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'medilac' ); ?></a>
    
    <?php
        echo ultraaddons_elementor_display_content();
    ?>