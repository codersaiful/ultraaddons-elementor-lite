<?php

use UltraAddons\Core\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$ultraaddons_form_datas = filter_input_array(INPUT_POST);

$ultraaddons_form_datas = apply_filters( 'ultraaddons/admin/setting/save_data', $ultraaddons_form_datas, 'settings' );

$ultraaddons_key = Settings::$ultraaddons_key; //'ultraaddons_settings'

$ultraaddons_saved = false;
if( $ultraaddons_form_datas && $ultraaddons_key ){
    // Verify nonce before processing form data
    $nonce = isset( $ultraaddons_form_datas['_ultraaddons_settings_nonce'] ) ? $ultraaddons_form_datas['_ultraaddons_settings_nonce'] : '';
    if ( wp_verify_nonce( $nonce, 'ultraaddons_settings_save' ) ) {
        /**
         * Action hook for when save data
         */
        do_action( 'ultraaddons/admin/setting/on_save', $ultraaddons_form_datas, $ultraaddons_key );
        update_option( $ultraaddons_key, $ultraaddons_form_datas );
        $ultraaddons_saved = true;
    }
}
$ultraaddons_current_data = Settings::get_data();


$ultraaddons_category_slug = Settings::get_widget_category();
?>

<div class="ultraaddons-section ua-settings-page">

    <?php if ( $ultraaddons_saved ) : ?>
    <div class="ua-notice ua-notice-success">
        <span class="dashicons dashicons-yes-alt"></span>
        <?php echo esc_html__( 'Settings saved successfully!', 'ultraaddons-elementor-lite' ); ?>
    </div>
    <?php endif; ?>

    <div class="ua-settings-grid">

        <!-- Main Settings Card -->
        <div class="ua-card">
            <div class="ua-card-header">
                <span class="ua-card-icon dashicons dashicons-admin-settings"></span>
                <div>
                    <h2 class="ua-card-title"><?php echo esc_html__( 'General Settings', 'ultraaddons-elementor-lite' ); ?></h2>
                    <p class="ua-card-subtitle"><?php echo esc_html__( 'Configure how UltraAddons behaves in your Elementor editor.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>
            <div class="ua-card-body">
                <form class="ua-settings-form" action="" method="post">

                    <?php wp_nonce_field( 'ultraaddons_settings_save', '_ultraaddons_settings_nonce' ); ?>

                    <?php
                    /**
                     * Action hook for setting
                     *
                     * @since 1.0.9.2
                     */
                    do_action( 'ultraaddons/admin/setting/form/top' );
                    ?>

                    <div class="ua-setting-row">
                        <div class="ua-setting-info">
                            <label class="ua-setting-label" for="ua-widget-in">
                                <?php echo esc_html__( 'Widget Panel Category', 'ultraaddons-elementor-lite' ); ?>
                            </label>
                            <p class="ua-setting-desc">
                                <?php echo esc_html__( 'Choose where UltraAddons widgets appear inside the Elementor editor panel.', 'ultraaddons-elementor-lite' ); ?>
                            </p>
                        </div>
                        <div class="ua-setting-control">
                            <select id="ua-widget-in" class="ua-select" name="widget_in">
                                <option value=""><?php echo esc_html__( 'UltraAddons (Default)', 'ultraaddons-elementor-lite' ); ?></option>
                                <option value="basic" <?php selected( $ultraaddons_category_slug, 'basic' ); ?>><?php echo esc_html__( 'Basic', 'ultraaddons-elementor-lite' ); ?></option>
                                <option value="general" <?php selected( $ultraaddons_category_slug, 'general' ); ?>><?php echo esc_html__( 'General', 'ultraaddons-elementor-lite' ); ?></option>
                            </select>
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

                    <div class="ua-card-footer">
                        <button class="ua-btn ua-btn-primary" type="submit">
                            <span class="dashicons dashicons-saved"></span>
                            <?php echo esc_html__( 'Save Changes', 'ultraaddons-elementor-lite' ); ?>
                        </button>
                    </div>

                </form>
            </div>
        </div><!-- /.ua-card -->

        <!-- Shortcode Card -->
        <div class="ua-card">
            <div class="ua-card-header">
                <span class="ua-card-icon dashicons dashicons-shortcode"></span>
                <div>
                    <h2 class="ua-card-title"><?php echo esc_html__( 'Template Shortcode', 'ultraaddons-elementor-lite' ); ?></h2>
                    <p class="ua-card-subtitle"><?php echo esc_html__( 'Embed any Elementor template anywhere on your site.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>
            <div class="ua-card-body">
                <div class="ua-shortcode-demo">
                    <code class="ua-code-block">[UltraAddons_Template id='<span class="ua-code-placeholder">POST_ID</span>']</code>
                </div>
                <p class="ua-setting-desc">
                    <?php echo esc_html__( 'Replace POST_ID with the ID of any Elementor page, post, or template. Works in posts, pages, Gutenberg blocks, text widgets, and more.', 'ultraaddons-elementor-lite' ); ?>
                </p>
                <div class="ua-shortcode-aliases">
                    <p class="ua-setting-desc"><?php echo esc_html__( 'Supported shortcode aliases:', 'ultraaddons-elementor-lite' ); ?></p>
                    <ul class="ua-aliases-list">
                        <li><code>[UltraAddons_Template id='123']</code></li>
                        <li><code>[UA_Template id='123']</code></li>
                        <li><code>[ULTRAADDONS_TEMPLATE id='123']</code></li>
                    </ul>
                    <p class="ua-setting-desc"><?php echo esc_html__( 'Supported attributes: id, template_id, post_id', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>
        </div><!-- /.ua-card -->

    </div><!-- /.ua-settings-grid -->

</div><!-- /.ua-settings-page -->
