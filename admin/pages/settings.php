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

<div class="ultraaddons-section ua-option-wrapper ua-settings-page">
    <div class="ua-section-inside">
        
        <!-- Modern Header Card -->
        <div class="ua-elements-header-card">
            <div class="ua-header-top-row">
                <div class="ua-title-area">
                    <div class="ua-title-group">
                        <h1 class="ua-main-title"><?php echo esc_html__( 'Settings', 'ultraaddons-elementor-lite' ); ?></h1>
                        <span class="ua-title-count-pill"><?php echo esc_html__( 'General Preferences', 'ultraaddons-elementor-lite' ); ?></span>
                    </div>
                    <p class="ua-sub-title"><?php echo esc_html__( 'Configure global UltraAddons preferences and Elementor editor categories.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>

                <div class="ua-header-actions">
                    <button type="submit" form="ua-settings-form" class="ua-btn-save-settings">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span><?php echo esc_html__( 'Save Changes', 'ultraaddons-elementor-lite' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-header-footer-form ua-settings-card-form" id="ua-settings-form" action="" method="post">
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
                        <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Widget Category in Elementor', 'ultraaddons-elementor-lite' ); ?></label>    
                        <select class="ultraddons-select ua-category-select" name="widget_in">
                            <option value="" ><?php echo esc_html__( 'Only UltraAddons (Default)', 'ultraaddons-elementor-lite' ); ?></option>
                            <option value="basic" <?php echo $ultraaddons_category_slug == 'basic' ? 'selected' : ''; ?>><?php echo esc_html__( 'Basic Category', 'ultraaddons-elementor-lite' ); ?></option>
                            <option value="general" <?php echo $ultraaddons_category_slug == 'general' ? 'selected' : ''; ?>><?php echo esc_html__( 'General Category', 'ultraaddons-elementor-lite' ); ?></option>
                        </select>
                        <div class="ua-form-message">
                            <p>
                                <?php echo esc_html__( 'By default, all widgets are grouped under "Addons - UltraAddons" in the Elementor edit panel. Choose Basic or General if you prefer them grouped with native Elementor widgets.', 'ultraaddons-elementor-lite' ); ?>
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
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit"><?php echo esc_html__( 'Save Changes', 'ultraaddons-elementor-lite' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Shortcode Helper Card -->
    <div class="ua-section-inside ua-settings-helper-section">
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">
                <div class="ua-extra-wrappper">
                    <div class="ua-content-section ua-shortcode-card">
                        <div class="ua-content-inside">
                            <div class="ua-content-info ua-shortcode-content">
                                <div class="ua-shortcode-header">
                                    <div class="ua-shortcode-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                                    </div>
                                    <div>
                                        <h3><?php echo esc_html__( 'Template Shortcode Helper', 'ultraaddons-elementor-lite' ); ?></h3>
                                        <p class="ua-shortcode-sub"><?php echo esc_html__( 'Embed any saved Elementor template anywhere across WordPress.', 'ultraaddons-elementor-lite' ); ?></p>
                                    </div>
                                </div>
                                <div class="ua-shortcode-snippets">
                                    <div class="ua-code-pill">
                                        <code>[UltraAddons_Template id='TEMPLATE_ID']</code>
                                    </div>
                                </div>
                                <p class="ua-shortcode-hint">
                                    <?php echo esc_html__( 'Replace TEMPLATE_ID with your post or template ID. You can paste this shortcode in classic widgets, Gutenberg shortcode blocks, theme templates, or sidebar areas.', 'ultraaddons-elementor-lite' ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
