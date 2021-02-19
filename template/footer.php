<?php

/**
 * Custom Footer file
 * by UltraAddons
 * 
 * @package UltraAddons
 * @category Core
 * 
 * @since 1.0.0.10
 */
?>
</div> <!-- /.page-conainer -->
<?php

$_header_footer_info = get_option( UltraAddons\Core\Header_Footer::$key );
$footer_id = $_header_footer_info['footer_id'];
echo ultraaddons_elementor_display_content( $footer_id );

wp_footer(); 
?>
</body>
</html>