<?php

use UltraAddons\Core\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$form_datas = filter_input_array(INPUT_POST);

$form_datas = apply_filters( 'ultraaddons/admin/setting/save_data', $form_datas, 'settings' );

$key = Settings::$key; //'ultraaddons_settings'

if( $form_datas && $key ){
    /**
     * Action hook for when save data
     */
    do_action( 'ultraaddons/admin/setting/on_save', $form_datas, $key );
    update_option( $key, $form_datas );
}
$current_data = Settings::get_data();


$category_slug = Settings::get_widget_category();
?>

<div class="ultraaddons-section ua-option-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Settings', 'ultraaddons' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-header-footer-form" action="" method="post">
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
                        <label class="field-label field-label-header-choose"><?php echo esc_html__( 'Widget Showing in', 'ultraaddons' ); ?></label>    
                        <select class="ultraddons-select" name="widget_in">
                            <option value="" ><?php echo esc_html__( 'Only UltraAddons', 'ultraaddons' ); ?></option>
                            <option value="basic" <?php echo $category_slug == 'basic' ? 'selected' : ''; ?>><?php echo esc_html__( 'Basic', 'ultraaddons' ); ?></option>
                            <option value="general" <?php echo $category_slug == 'general' ? 'selected' : ''; ?>><?php echo esc_html__( 'General', 'ultraaddons' ); ?></option>
                            
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
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
