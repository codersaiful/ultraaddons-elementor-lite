<?php

use UltraAddons\Classes\Template_List;
use UltraAddons\Core\Header_Footer;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$form_datas = filter_input_array(INPUT_POST);
do_action( 'ultraaddons_save_data', $form_datas, 'header_foooter' );

$key = Header_Footer::$key;


if( $form_datas && $key ){
    update_option( $key, $form_datas );
}
$current_data = Header_Footer::get_data();
var_dump($current_data);

$template_obj = new Template_List();
$templates = $template_obj->get_templates();




?>
<form style="" action="" class="" method="POST"> 
    
    
    
<?php
if( is_array( $templates ) && count( $templates ) > 0 ){
?>
    
    <label><?php echo esc_html__( 'Select Header', 'ultraaddons' ); ?></label>    
    <select class="ultraddons-select" name="header_id">
        <option value=""><?php echo esc_html__( 'None', 'ultraaddons' ); ?></option>
        <?php
        foreach( $templates as $templ_id => $templ_name ){
            $selected = isset( $current_data['header_id'] ) && $current_data['header_id'] == $templ_id ? 'selected' : '';
            echo "<option value='{$templ_id}' $selected>{$templ_name}</option>";
        }
        ?>
    </select>

    
    <label><?php echo esc_html__( 'Select Footer', 'ultraaddons' ); ?></label>    
    <select class="ultraddons-select" name="footer_id">
        <option value=""><?php echo esc_html__( 'None', 'ultraaddons' ); ?></option>
        <?php
        foreach( $templates as $templ_id => $templ_name ){
            $selected = isset( $current_data['footer_id'] ) && $current_data['footer_id'] == $templ_id ? 'selected' : '';
            echo "<option value='{$templ_id}' $selected>{$templ_name}</option>";
        }
        ?>
    </select>

    
    
<?php
}else{
?>
    <h2 class="ultraaddons-header-footer-not"><?php echo esc_html__( "There is no Template founded in Elementor Library", 'ultraaddons' ); ?></h2>
<?php   
}?>
    <label>Templating Type</label>
    <input type="text" value="<?php echo isset( $current_data['type'] ) ? $current_data['type'] : 'php'; ?>" name="type">
    <button type="submit"><?php echo esc_html__( 'Submit', 'ultraaddons' ); ?></button>
</form>