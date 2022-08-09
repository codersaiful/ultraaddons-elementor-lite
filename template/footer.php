<?php
use \UltraAddons\Core\Header_Footer;
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


echo ultraaddons_elementor_display_content( Header_Footer::get_footer_id() );

wp_footer(); 
?>
</body>
</html>