<?php

use UltraAddons\Core\Extensions_Manager;

defined( 'ABSPATH' ) || die();

$ultraaddons_items = Extensions_Manager::get_list();
if ( is_array( $ultraaddons_items ) ) {
    uasort( $ultraaddons_items, function ( $a, $b ) {
        return strcasecmp( $a['name'] ?? '', $b['name'] ?? '' );
    } );
}
if ( isset( $_POST['ultraaddons_nonce'] ) ) {
    if ( ! current_user_can( ULTRA_ADDONS_CAPABILITY ) ) {
        wp_die( esc_html__( 'You are not allowed to manage UltraAddons extensions.', 'ultraaddons-elementor-lite' ) );
    }

    check_admin_referer( 'ultraaddons_save_extensions', 'ultraaddons_nonce' );
    $submitted_items = isset( $_POST['item'] ) && is_array( $_POST['item'] )
        ? array_map( 'sanitize_text_field', wp_unslash( $_POST['item'] ) )
        : [];
    $disabled_items = array_values( array_intersect( $submitted_items, array_keys( $ultraaddons_items ) ) );
    update_option( Extensions_Manager::$disabled_items_key, $disabled_items );
}
$ultraaddons_disable_item = Extensions_Manager::disableExtensionKeys();
?>

<div class="ultraaddons-section ua-option-wrapper ua-extensions-page">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Extension', 'ultraaddons-elementor-lite' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-option-list-form" action="" method="post">
                    <?php wp_nonce_field( 'ultraaddons_save_extensions', 'ultraaddons_nonce' ); ?>
                    <div class="ua-option-item-wrappper">
                        <?php 
                        foreach( $ultraaddons_items as $ultraaddons_class_name => $ultraaddons_item ){

                            $ultraaddons_name = isset( $ultraaddons_item['name'] ) ? $ultraaddons_item['name'] : false;
                            $ultraaddons_icon = isset( $ultraaddons_item['icon'] ) ? $ultraaddons_item['icon'] : false;
                            $cat = isset( $ultraaddons_item['cat'] ) && is_array( $ultraaddons_item['cat'] ) ? $ultraaddons_item['cat'] : [];
                            $ultraaddons_free_pro = isset( $ultraaddons_item['is_pro'] ) && $ultraaddons_item['is_pro'] ? 'pro' : 'free';
                            
                            /**
                             * On or OFF feature will stay or not
                             * it will depend on this class
                             * and
                             * we customized this class based on
                             * pro available or not.
                             * 
                             * zodi pr thake tahole sob e on or off kora zabe.
                             * r jodi na thake, tahole sudhu free guloi onOff kora zabe.
                             * 
                             * eta ber korar jonno ami
                             * prothome check korechi, see free naki pro
                             * jodi free hoy to sob somoy change able.
                             * r jodi pr hoy, tobe pr thaklei changeable hobe.
                             * 
                             * @since 1.0.7.17
                             */
                            $ultraaddons_item_oo_option = isset( $ultraaddons_item['is_pro'] ) && $ultraaddons_item['is_pro'] && ! ultraaddons_is_pro()  ? 'item_on_off_disable' : 'item_on_off_enable';
                            
                            
                            
                            $ultraaddons_checkbox = in_array( $ultraaddons_class_name, $ultraaddons_disable_item ) ? 'checked' : '';
                            $ultraaddons_enbl_disbl_class = in_array( $ultraaddons_class_name, $ultraaddons_disable_item ) ? 'disabled' : 'enabled';
                            
                            $ultraaddons_checkbox_id = 'checkbox_' . $ultraaddons_class_name;
                            $ultraaddons_html_class = [];
//                            $ultraaddons_html_class[] = $ultraaddons_name;
                            $ultraaddons_html_class[] = $ultraaddons_enbl_disbl_class;
                            $ultraaddons_html_class[] = $ultraaddons_item_oo_option;
                            //$ultraaddons_html_class[] = $ultraaddons_icon;
                            $ultraaddons_html_class[] = $ultraaddons_free_pro;
                            $ultraaddons_html_class[] = $ultraaddons_class_name;
                        ?>
                        <label data-name="<?php echo esc_attr( $ultraaddons_name ); ?>" 
                             for="<?php echo esc_attr( $ultraaddons_checkbox_id ); ?>"
                             data-object_name="<?php echo esc_attr( $ultraaddons_class_name ); ?>"
                             data-category="<?php echo esc_attr( implode( ',', $cat ) ); ?>"
                             data-type="<?php echo esc_attr( $ultraaddons_free_pro ); ?>"
                             class="ua-option-item <?php echo esc_attr( implode( " ", $ultraaddons_html_class ) ); ?>">
                            <div class="ua-option-item-inside">
                                <span class="ua-option-version-type ua-option-version-type-<?php echo esc_attr( $ultraaddons_free_pro ); ?>"><?php echo $ultraaddons_free_pro == 'pro' ? esc_html__( 'Pro', 'ultraaddons-elementor-lite' ) : esc_html__( 'Free', 'ultraaddons-elementor-lite' ); ?></span>
                                <i class="ua-option-icon <?php echo esc_attr( $ultraaddons_icon ); ?>"></i>
                                <h2 class="ua-item-name"><?php echo esc_html( $ultraaddons_name ); ?></h2>
                                <div class="ua-option-checkbox">
                                    <input class="ua-checkbox-hidden" id="<?php echo esc_attr( $ultraaddons_checkbox_id ); ?>" type="checkbox" name="item[]" value="<?php echo esc_attr( $ultraaddons_class_name ); ?>" <?php echo esc_attr( $ultraaddons_checkbox ); ?>>
                                    <div class="ua-designed-checkbox"></div>
                                </div>
                            </div>
                        </label>
                        <?php } ?>
                    </div>
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit" name="submit" value="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons-elementor-lite' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<?php
/**
* Modal for pro Extension
* @aurhor B M Rafiul Alam
* Email: bmrafiul.alam@gmail.com
* @since 1.1.0.10
*/
?>
<div class="remodal" data-remodal-id="modal">
  <button data-remodal-action="close" class="remodal-close"></button>
  <img class="popup-image" src=" <?php echo esc_attr( ULTRA_ADDONS_ASSETS ) . 'images/popup-pro.png'; ?>">
  <h1>Go Pro</h1>
  <p>
    Unlock 4+ amazing extension to build awesome websites.
  </p>
  <br>
  <a href="https://ultraaddons.com/pricing/" target="_blank" class="remodal-confirm">Upgrade Now</a>
</div>
