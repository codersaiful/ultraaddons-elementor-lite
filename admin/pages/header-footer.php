<?php

use UltraAddons\Classes\Template_List;
use UltraAddons\Core\Header_Footer;
use UltraAddons\WP\Header_Footer_Post as HF_Post;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$ultraaddons_form_datas = filter_input_array(INPUT_POST);

do_action( 'ultraaddons_save_data', $ultraaddons_form_datas, 'header_foooter' );

$ultraaddons_key = Header_Footer::$ultraaddons_key;


if( $ultraaddons_form_datas && $ultraaddons_key ){
    // update_option( $ultraaddons_key, $ultraaddons_form_datas );
}
$ultraaddons_current_data = Header_Footer::get_data();
$type = isset( $ultraaddons_current_data['type'] ) ? $ultraaddons_current_data['type'] : '';
$ultraaddons_wrapper = isset( $ultraaddons_current_data['wrapper'] ) ? $ultraaddons_current_data['wrapper'] : 'box';;

$ultraaddons_template_obj = new Template_List();
$ultraaddons_templates = $ultraaddons_template_obj->get_templates();



$ultraaddons_add_new_elementor_template = admin_url( 'post-new.php?post_type=' . HF_Post::$post_type );
?>

<div class="ultraaddons-section ua-option-wrapper ua-hf-page">
    <div class="ua-section-inside">
        
        <!-- Modern Header Card -->
        <div class="ua-elements-header-card">
            <div class="ua-header-top-row">
                <div class="ua-title-area">
                    <div class="ua-title-group">
                        <h2 class="ua-main-title"><?php echo esc_html__( 'Header & Footer', 'ultraaddons-elementor-lite' ); ?></h2>
                        <span class="ua-title-count-pill"><?php echo esc_html__( 'Template Builder', 'ultraaddons-elementor-lite' ); ?></span>
                    </div>
                    <p class="ua-sub-title"><?php echo esc_html__( 'Assign Elementor templates as site-wide or conditional header and footer.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>

                <div class="ua-header-actions">
                    <a href="<?php echo esc_url( $ultraaddons_add_new_elementor_template ); ?>" target="_blank" class="ua-hero-btn ua-hero-btn-primary" style="text-decoration: none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span><?php echo esc_html__( 'Create Template', 'ultraaddons-elementor-lite' ); ?></span>
                    </a>

                    <button type="submit" form="ua-hf-form" class="ua-btn-save-settings">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span><?php echo esc_html__( 'Save Changes', 'ultraaddons-elementor-lite' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-header-footer-form ua-settings-card-form" id="ua-hf-form" action="" method="post">
                    <div class="ua-form-wrappper">
                    <?php
                    if( is_array( $ultraaddons_templates ) && count( $ultraaddons_templates ) > 0 ){
                    ?>
                        <div class="ultraaddons-field-container">
                            <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Select Header', 'ultraaddons-elementor-lite' ); ?></label>    
                            <select class="ultraddons-select" name="header_id">
                                <option value=""><?php echo esc_html__( 'None', 'ultraaddons-elementor-lite' ); ?></option>
                                <?php
                                foreach( $ultraaddons_templates as $ultraaddons_templ_id => $ultraaddons_templ_name ){
                                    $ultraaddons_selected = isset( $ultraaddons_current_data['header_id'] ) && $ultraaddons_current_data['header_id'] == $ultraaddons_templ_id ? 'selected' : '';
                                ?>
                                <option value='<?php echo esc_attr( $ultraaddons_templ_id ); ?>' <?php echo esc_attr( $ultraaddons_selected ); ?>><?php echo esc_html( $ultraaddons_templ_name ); ?></option>
                                <?php 
                                }
                                ?>
                            </select>
                            <div class="ua-form-message">
                                <p>
                                   Template list is coming from Elementor Template. If you already made your header in Elementor 
                                   Template, Then Choose any one. Otherwise, 
                                   Create new 
                                   <a href="<?php echo esc_url( $ultraaddons_add_new_elementor_template ); ?>" target="_blank">
                                       Header as Elementor Template
                                   </a>.
                                </p>
                            </div>
                        </div>
                        <div class="ultraaddons-field-container">
                            <label class="field-label field-label-footer-choose"><?php echo esc_html__( 'Select Footer', 'ultraaddons-elementor-lite' ); ?></label>    
                            <select class="ultraddons-select" name="footer_id">
                                <option value=""><?php echo esc_html__( 'None', 'ultraaddons-elementor-lite' ); ?></option>
                                <?php
                                foreach( $ultraaddons_templates as $ultraaddons_templ_id => $ultraaddons_templ_name ){
                                    $ultraaddons_selected = isset( $ultraaddons_current_data['footer_id'] ) && $ultraaddons_current_data['footer_id'] == $ultraaddons_templ_id ? 'selected' : '';
                                ?>
                                <option value='<?php echo esc_attr( $ultraaddons_templ_id ); ?>' <?php echo esc_attr( $ultraaddons_selected ); ?>><?php echo esc_html( $ultraaddons_templ_name ); ?></option>
                                <?php 
                                }
                                ?>
                            </select>
                            <div class="ua-form-message">
                                <p>
                                   Template list is coming from Elementor Template. If you already made your footer in Elementor 
                                   Template, Then Choose any one. Otherwise, 
                                   Create new 
                                   <a href="<?php echo esc_url( $ultraaddons_add_new_elementor_template ); ?>" target="_blank">
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
                            <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Container Size', 'ultraaddons-elementor-lite' ); ?></label>    
                            <select class="ultraddons-select" name="wrapper">
                                <option value="box" <?php echo $ultraaddons_wrapper == 'box' ? 'selected' : ''; ?>><?php echo esc_html__( 'Box', 'ultraaddons-elementor-lite' ); ?></option>
                                <option value="flued" <?php echo $ultraaddons_wrapper == 'flued' ? 'selected' : ''; ?>><?php echo esc_html__( 'Flued', 'ultraaddons-elementor-lite' ); ?></option>
                                
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
                        <h2 class="ultraaddons-header-footer-not"><?php echo esc_html__( "There is no Template founded in Elementor Library", 'ultraaddons-elementor-lite' ); ?></h2>
                    <?php } ?>   

                    </div> <!-- /.ua-form-wrappper -->
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons-elementor-lite' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
