<?php

use UltraAddons\Core\Extensions_Manager;

defined( 'ABSPATH' ) || die();

$updated = filter_input_array( INPUT_POST );
if( $updated ){
    $update_value = false;
    if( ! empty( $updated['item'] ) ){
        $update_value = $updated['item'];
    }
    update_option( Extensions_Manager::$disabled_items_key, $update_value );
}

$items = Extensions_Manager::get_list();
$disable_item = Extensions_Manager::disableExtensionKeys();
?>

<div class="ultraaddons-section ua-option-wrapper ua-extensions-page">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Extension', 'ultraaddons' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-option-list-form" action="" method="post">
                    <div class="ua-option-item-wrappper">
                        <?php 
                        foreach( $items as $class_name => $item ){

                            $name = isset( $item['name'] ) ? $item['name'] : false;
                            $icon = isset( $item['icon'] ) ? $item['icon'] : false;
                            $cat = isset( $item['cat'] ) && is_array( $item['cat'] ) ? $item['cat'] : [];
                            $free_pro = isset( $item['is_pro'] ) && $item['is_pro'] ? 'pro' : 'free';
                            
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
                            $item_oo_option = isset( $item['is_pro'] ) && $item['is_pro'] && ! ultraaddons_is_pro()  ? 'item_on_off_disable' : 'item_on_off_enable';
                            
                            
                            
                            $checkbox = in_array( $class_name, $disable_item ) ? 'checked' : '';
                            $enbl_disbl_class = in_array( $class_name, $disable_item ) ? 'disabled' : 'enabled';
                            
                            $checkbox_id = 'checkbox_' . $class_name;
                            $html_class = [];
//                            $html_class[] = $name;
                            $html_class[] = $enbl_disbl_class;
                            $html_class[] = $item_oo_option;
                            //$html_class[] = $icon;
                            $html_class[] = $free_pro;
                            $html_class[] = $class_name;
                        ?>
                        <label data-name="<?php echo esc_attr( $name ); ?>" 
                             for="<?php echo esc_attr( $checkbox_id ); ?>"
                             data-object_name="<?php echo esc_attr( $class_name ); ?>"
                             data-category="<?php echo esc_attr( implode( ',', $cat ) ); ?>"
                             data-type="<?php echo esc_attr( $free_pro ); ?>"
                             class="ua-option-item <?php echo esc_attr( implode( " ", $html_class ) ); ?>">
                            <div class="ua-option-item-inside">
                                <span class="ua-option-version-type ua-option-version-type-<?php echo esc_attr( $free_pro ); ?>"><?php echo $free_pro == 'pro' ? esc_html__( 'Pro', 'ultraaddons' ) : esc_html__( 'Free', 'ultraaddons' ); ?></span>
                                <i class="ua-option-icon <?php echo esc_attr( $icon ); ?>"></i>
                                <h2 class="ua-item-name"><?php echo esc_html( $name ); ?></h2>
                                <div class="ua-option-checkbox">
                                    <input class="ua-checkbox-hidden" id="<?php echo esc_attr( $checkbox_id ); ?>" type="checkbox" name="item[]" value="<?php echo esc_attr( $class_name ); ?>" <?php echo esc_attr( $checkbox ); ?>>
                                    <div class="ua-designed-checkbox"></div>
                                </div>
                            </div>
                        </label>
                        <?php } ?>
                    </div>
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit" name="submit" value="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons' ); ?></button>
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
  <img class="popup-image" src=" <?php echo ULTRA_ADDONS_ASSETS . 'images/popup-pro.png'; ?>">
  <h1>Go Pro</h1>
  <p>
    Unlock 4+ amazing extension to build awesome websites.
  </p>
  <br>
  <a href="https://ultraaddons.com/pricing/" target="_blank" class="remodal-confirm">Upgrade Now</a>
</div>
