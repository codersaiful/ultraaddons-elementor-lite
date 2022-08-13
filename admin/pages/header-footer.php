<?php

use UltraAddons\Classes\Template_List;
use UltraAddons\Core\Header_Footer;
use UltraAddons\WP\Header_Footer_Post as HF_Post;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$form_datas = filter_input_array(INPUT_POST);

do_action( 'ultraaddons_save_data', $form_datas, 'header_foooter' );

$key = Header_Footer::$key;


if( $form_datas && $key ){
    // update_option( $key, $form_datas );
}
$current_data = Header_Footer::get_data();
$type = isset( $current_data['type'] ) ? $current_data['type'] : '';
$wrapper = isset( $current_data['wrapper'] ) ? $current_data['wrapper'] : 'box';;

$template_obj = new Template_List();
$templates = $template_obj->get_templates();



$add_new_elementor_template = admin_url( 'post-new.php?post_type=' . HF_Post::$post_type );
?>

<div class="ultraaddons-section ua-option-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Header & Footer', 'ultraaddons' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-header-footer-form" action="" method="post">
                    <div class="ua-form-wrappper">
                    <?php
                    if( is_array( $templates ) && count( $templates ) > 0 ){
                    ?>
                        <div class="ultraaddons-field-container">
                            <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Select Header', 'ultraaddons' ); ?></label>    
                            <select class="ultraddons-select" name="header_id">
                                <option value=""><?php echo esc_html__( 'None', 'ultraaddons' ); ?></option>
                                <?php
                                foreach( $templates as $templ_id => $templ_name ){
                                    $selected = isset( $current_data['header_id'] ) && $current_data['header_id'] == $templ_id ? 'selected' : '';
                                    echo "<option value='{$templ_id}' $selected>{$templ_name}</option>";
                                }
                                ?>
                            </select>
                            <div class="ua-form-message">
                                <p>
                                   Template list is coming from Elementor Template. If you already made your header in Elementor 
                                   Template, Then Choose any one. Otherwise, 
                                   Create new 
                                   <a href="<?php echo esc_url( $add_new_elementor_template ); ?>" target="_blank">
                                       Header as Elementor Template
                                   </a>.
                                </p>
                            </div>
                        </div>
                        <div class="ultraaddons-field-container">
                            <label class="field-label field-label-footer-choose"><?php echo esc_html__( 'Select Footer', 'ultraaddons' ); ?></label>    
                            <select class="ultraddons-select" name="footer_id">
                                <option value=""><?php echo esc_html__( 'None', 'ultraaddons' ); ?></option>
                                <?php
                                foreach( $templates as $templ_id => $templ_name ){
                                    $selected = isset( $current_data['footer_id'] ) && $current_data['footer_id'] == $templ_id ? 'selected' : '';
                                    echo "<option value='{$templ_id}' $selected>{$templ_name}</option>";
                                }
                                ?>
                            </select>
                            <div class="ua-form-message">
                                <p>
                                   Template list is coming from Elementor Template. If you already made your footer in Elementor 
                                   Template, Then Choose any one. Otherwise, 
                                   Create new 
                                   <a href="<?php echo esc_url( $add_new_elementor_template ); ?>" target="_blank">
                                       Footer as Elementor Template
                                   </a>.
                                </p>
                            </div>
                        </div>
                        <div class="ultraaddons-field-container">
                            <!--
                            Supported:
                            php: it will replace header file location with our plugin's header replace. header template will come from our plugins header file.
                            css: it will just hide theme's header by css. which classs should hav site-header, or id masterhead or side-header
                            addintional: for this option, header and footer will add as additional 
                            -->
                            <label class="field-label field-label-template-system">Template System</label>
                            <div class="ua-radion-type">
                                <label for="ua-header-footer-dir-change" class="radio-label radio-label-hf">
                                    <div class="ua-radio-top">
                                        <input <?php echo $type == 'php' ? 'checked' : ''; ?>
                                            name="type" value="php" class="ua-hf-type-radio" type="radio" id="ua-header-footer-dir-change">
                                        <h4 class="ua-hf-radio-label-title">Header Directory Change</h4>
                                    </div>
                                    <div class="ua-form-message ua-radio-message">
                                           Template list is coming from Elementor Template. If you already made your header in Elementor 
                                    </div>
                                </label>
                                
                                <label for="ua-header-footer-by-css" class="radio-label radio-label-hf">
                                    <div class="ua-radio-top">
                                        <input <?php echo $type == 'css' ? 'checked' : ''; ?>
                                            name="type" value="css" class="ua-hf-type-radio" type="radio" id="ua-header-footer-by-css">
                                        <h4 class="ua-hf-radio-label-title">Override by CSS</h4>
                                    </div>
                                    <div class="ua-form-message ua-radio-message">
                                           To this system, Our plugin will not replace header.php file or don't change header file location.
                                           We will hide current theme's header by css. we have used css <code>display: none;</code> for some selector:<b>.site-header/#site-header/#masthead/.site-footer/#site-footer</b>.<br>
                                           If not available these class to your theme, Our plugin will not able to hide your theme's header.<br>
                                           <i>In this situation, you have to hide your header or footer manually by css code.</i> You can add your css code to Customizer.
                                    </div>
                                </label>
                                <label for="ua-header-footer-additional" class="radio-label radio-label-hf">
                                    <div class="ua-radio-top">
                                        <input <?php echo $type == 'additional' ? 'checked' : ''; ?>
                                            name="type" value="additional" class="ua-hf-type-radio" type="radio" id="ua-header-footer-additional">
                                        <h4 class="ua-hf-radio-label-title">Header Content as Additional </h4>
                                    </div>
                                    <div class="ua-form-message ua-radio-message">
                                           This method/system will not hide current header or footer. Instead: Our plugin will add Additional Header part or Footer part for your site.
                                           Header and Footer Template will add at the top of current Header/Footer.
                                    </div>
                                </label>
                                
                                
                            </div>
                        </div>

                        <div class="ultraaddons-field-container field-container-size">
                            <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Container Size', 'ultraaddons' ); ?></label>    
                            <select class="ultraddons-select" name="wrapper">
                                <option value="box" <?php echo $wrapper == 'box' ? 'selected' : ''; ?>><?php echo esc_html__( 'Box', 'ultraaddons' ); ?></option>
                                <option value="flued" <?php echo $wrapper == 'flued' ? 'selected' : ''; ?>><?php echo esc_html__( 'Flued', 'ultraaddons' ); ?></option>
                                
                            </select>
                            <div class="ua-form-message">
                                <p>
                                    Container size, Only for Template System: <b>Header Directory Change</b>. Otherwise, Container Size will not impact to your theme.
                                    for <b>Box</b> size, container width accordingly 1170px,960px,730px,540px based on Browser Window size. On the other hand, for <b>Flued</b>, width will be 100%;
                                    our container class is: <code>.ultraaddons-container</code>.
                                </p>
                            </div>
                        </div>

                    <?php
                    }else{
                    ?>
                        <h2 class="ultraaddons-header-footer-not"><?php echo esc_html__( "There is no Template founded in Elementor Library", 'ultraaddons' ); ?></h2>
                    <?php } ?>   

                    </div> <!-- /.ua-form-wrappper -->
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
