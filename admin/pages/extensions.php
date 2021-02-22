<?php

use UltraAddons\Core\Extensions_Manager;

defined( 'ABSPATH' ) || die();

if( filter_input_array( INPUT_POST ) ){
    $updated = filter_input_array( INPUT_POST );
    if( ! empty( $updated['extension'] ) ){
        update_option( Extensions_Manager::$disabled_extenstions_key, $updated['extension'] );
    }
}

$extensions = Extensions_Manager::get_list();
$disable_extension = Extensions_Manager::disableExtensionKeys();
var_dump($disable_extension);
?>

<div class="ultraaddons-section ua-option-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Extension', 'ultraaddons' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-option-list-form" action="" method="post">
                    <div class="ua-option-item-wrappper">
                        <?php 
                        foreach( $extensions as $class_name => $extension ){

                            $name = isset( $extension['name'] ) ? $extension['name'] : false;
                            $icon = isset( $extension['icon'] ) ? $extension['icon'] : false;
                            $cat = isset( $extension['cat'] ) && is_array( $extension['cat'] ) ? $extension['cat'] : [];
                            $free_pro = isset( $extension['is_free'] ) && $extension['is_free'] ? 'free' : 'pro';
                            
                            $checkbox = in_array( $class_name, $disable_extension ) ? 'checked' : '';
                            $checkbox_id = 'checkbox_' . $class_name;
                            $html_class = [];
                            $html_class[] = $name;
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
                                <h2 class="ua-widget-name"><?php echo esc_html( $name ); ?></h2>
                                <div class="ua-option-checkbox">
                                    <input class="ua-checkbox-hidden" id="<?php echo esc_attr( $checkbox_id ); ?>" type="checkbox" name="extension[]" value="<?php echo esc_attr( $class_name ); ?>" <?php echo esc_attr( $checkbox ); ?>>
                                    <div class="ua-designed-checkbox"></div>
                                </div>
                            </div>
                        </label>
                        <?php } ?>
                    </div>
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>