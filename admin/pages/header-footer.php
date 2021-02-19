<?php

use UltraAddons\Core\Template_List;

defined( 'ABSPATH' ) || die();


$template_obj = new Template_List();
$templates = $template_obj->get_templates();

var_dump($templates);
if( is_array( $templates ) && count( $templates ) > 0 ){
?>
<select class="ultraddons-select" name="ultraddons-header-id">
    <?php
    foreach( $templates as $templ_id => $templ_name ){
        echo "<option value='{$templ_id}'>{$templ_name}</option>";
    }
    ?>
</select>
<?php
}else{
?>
    <h2 class="ultraaddons-header-footer-not"><?php echo esc_html__( "There is no Template founded in Elementor Library", 'ultraaddons' ); ?></h2>
<?php   
}