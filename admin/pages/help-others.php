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
$ultraaddons_form_datas = filter_input_array(INPUT_POST);

do_action( 'ultraaddons_save_data', $ultraaddons_form_datas, 'settings' );

$ultraaddons_key = Settings::$ultraaddons_key;

if( $ultraaddons_form_datas && $ultraaddons_key ){
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


                    <div class="ua-form-wrappper">
                    
                        
                    


                    <div class="ua-content-section">
                        <div class="ua-content-inside">
                            <div class="ua-content-info ua-shortcode-content">
                                <h3><?php echo esc_html__( 'Shortcode', 'ultraaddons-elementor-lite' ); ?> <small><?php echo esc_html( "[UltraAddons_Template id='template_id']" ); ?></small></h3>
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
