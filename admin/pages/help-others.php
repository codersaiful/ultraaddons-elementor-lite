<?php

/**
 * This file has removed from latest version
 * but stil I kept it plugin
 * because it can be need later
 * 
 * I will remove it from menu
 */

use UltraAddons\Core\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Controlling Form Submission
 */
$form_datas = filter_input_array(INPUT_POST);

do_action( 'ultraaddons_save_data', $form_datas, 'settings' );

$key = Settings::$key;

if( $form_datas && $key ){
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


                    <div class="ua-form-wrappper">
                    
                        
                    


                    <div class="ua-content-section">
                        <div class="ua-content-inside">
                            <div class="ua-content-info ua-shortcode-content">
                                <h3><?php echo esc_html__( 'Shortcode', 'ultraaddons' ); ?> <small><?php echo esc_html( "[UltraAddons_Template id='template_id']" ); ?></small></h3>
                                <p>UltraAddons provide a shortcode <code>[UltraAddons_Template id='123']</code>. Here 
                                    123 is a POST_ID. Use any Elementor page/Template's POST_ID as id. Use Anywhere.<br>
                                    Suppose: you want to show any Elementor Item/Widget/Template in widget, or in any WordPress post or in Guttenberg block. 
                                    Just use this shortcode.
                                </p>
                            </div>
                        </div>
                    </div>

                     

                    </div> <!-- /.ua-form-wrappper -->
                    
                
            </div>
        </div>
    </div>
</div>
