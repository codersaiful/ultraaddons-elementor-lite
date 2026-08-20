<?php

use UltraAddons\Core\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$ultraaddons_key = Settings::$ultraaddons_key; //'ultraaddons_settings'
$ultraaddons_form_datas = [];

if ( isset( $_POST['ultraaddons_nonce'] ) && $ultraaddons_key ) {
    if ( ! current_user_can( ULTRA_ADDONS_CAPABILITY ) ) {
        wp_die( esc_html__( 'You are not allowed to manage UltraAddons settings.', 'ultraaddons-elementor-lite' ) );
    }

    check_admin_referer( 'ultraaddons_save_settings', 'ultraaddons_nonce' );
    $raw_form_data = wp_unslash( $_POST );
    unset( $raw_form_data['ultraaddons_nonce'], $raw_form_data['_wp_http_referer'] );
    $ultraaddons_form_datas = map_deep( $raw_form_data, 'sanitize_text_field' );

    if ( isset( $ultraaddons_form_datas['widget_in'] ) && ! in_array( $ultraaddons_form_datas['widget_in'], [ '', 'basic', 'general' ], true ) ) {
        $ultraaddons_form_datas['widget_in'] = '';
    }

    $ultraaddons_form_datas = apply_filters( 'ultraaddons/admin/setting/save_data', $ultraaddons_form_datas, 'settings' );
    /**
     * Action hook for when save data
     */
    do_action( 'ultraaddons/admin/setting/on_save', $ultraaddons_form_datas, $ultraaddons_key );
    update_option( $ultraaddons_key, $ultraaddons_form_datas );
}
$ultraaddons_current_data = Settings::get_data();


$ultraaddons_category_slug = Settings::get_widget_category();
?>

<div class="ultraaddons-section ua-option-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Settings', 'ultraaddons-elementor-lite' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-header-footer-form" action="" method="post">
                    <?php wp_nonce_field( 'ultraaddons_save_settings', 'ultraaddons_nonce' ); ?>
                    <div class="ua-form-wrappper">
                    
                        
                    <?php
                    /**
                     * Action hook for setting
                     * 
                     * @since 1.0.9.2
                     */
                    do_action( 'ultraaddons/admin/setting/form/top' );
                    ?>


                    <div class="ultraaddons-field-container field-container-category">
                        <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Widget Showing in', 'ultraaddons-elementor-lite' ); ?></label>    
                        <select class="ultraddons-select" name="widget_in">
                            <option value="" ><?php echo esc_html__( 'Only UltraAddons', 'ultraaddons-elementor-lite' ); ?></option>
                            <option value="basic" <?php echo $ultraaddons_category_slug == 'basic' ? 'selected' : ''; ?>><?php echo esc_html__( 'Basic', 'ultraaddons-elementor-lite' ); ?></option>
                            <option value="general" <?php echo $ultraaddons_category_slug == 'general' ? 'selected' : ''; ?>><?php echo esc_html__( 'General', 'ultraaddons-elementor-lite' ); ?></option>
                            
                        </select>
                        <div class="ua-form-message">
                            <p>
                                Widget shows in <b>Addons - UltraAddons</b> Category of Elementor(in Elementor Edit Screen). If you want to show UltraAddons Widget
                                    in Basic or General category, Choose and Save Change.
                            </p>
                        </div>
                    </div>

                    <?php
                    /**
                     * Action hook for setting
                     * 
                     * @since 1.0.9.2
                     */
                    do_action( 'ultraaddons/admin/setting/form/bottom' );
                    ?>
                     

                    </div> <!-- /.ua-form-wrappper -->
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons-elementor-lite' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    <div class="ua-section-inside">
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

            <h3 class="ua-section-title"><?php echo esc_html__( 'Other Features', 'ultraaddons-elementor-lite' ); ?></h3>
                    <div class="ua-extra-wrappper">

                        <div class="ua-content-section">
                            <div class="ua-content-inside">
                                <div class="ua-content-info ua-shortcode-content">
                                    <h3><?php echo esc_html__( 'Shortcode', 'ultraaddons-elementor-lite' ); ?> <small><?php echo esc_html( "[UltraAddons_Template id='template_id']" ); ?></small></h3>
                                    <p>UltraAddons provide a shortcode <code>[UltraAddons_Template id='123']</code>. Here 
                                        123 is a POST_ID. Use any Elementor page/Template's POST_ID as id. Use Anywhere.<br>
                                        Suppose: you want to show any Elementor Item/Widget/Template in widget, or in any WordPress post or in Guttenberg block. 
                                        Just use this shortcode.<br>
                                        <code>`[UltraAddons_Template id='1234']`,`[UA_Template id='1234']` and `[ULTRAADDONS_TEMPLATE id='1234']`</code>
                                        <br>
                                        <code>Attribute: `id` or `template_id` or `post_id`</code>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.ua-form-wrappper -->
            </div>
        </div>
    </div>
</div>
