<?php
use \UltraAddons\Classes\Header_Footer_Render as HF_Render;
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


echo ultraaddons_elementor_display_content( HF_Render::get_footer_id() );

wp_footer(); 
?>
</body>
</html>