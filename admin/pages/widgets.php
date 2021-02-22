<?php

use UltraAddons\Core\Widgets_Manager;

defined( 'ABSPATH' ) || die();

if( filter_input_array( INPUT_POST ) ){
    $updated = filter_input_array( INPUT_POST );
    if( ! empty( $updated['widget'] ) ){
        update_option( Widgets_Manager::$disabled_widgets_key, $updated['widget'] );
    }
}

$widgets = Widgets_Manager::widgets();
$disable_widgets = Widgets_Manager::disableWidgetKeys();

?>

<div class="ultraaddons-section ua-widgets-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1><?php echo esc_html__( 'Widgets', 'ultraaddons' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">

                <form class="ua-widget-list-form" action="" method="post">
                    <div class="ua-widget-item-wrappper">
                        <?php 
                        foreach( $widgets as $class_name => $widget ){

                            $name = $widget['name'];
                            $icon = $widget['icon'];
                            $cat = isset( $widget['cat'] ) && is_array( $widget['cat'] ) ? $widget['cat'] : [];
                            $free_pro = isset( $widget['is_free'] ) && $widget['is_free'] ? 'free' : 'pro';
                            
                            $checkbox = in_array( $class_name, $disable_widgets ) ? 'checked' : '';
                            
                            $html_class = [];
                            $html_class[] = $name;
                            //$html_class[] = $icon;
                            $html_class[] = $free_pro;
                            $html_class[] = $class_name;
                        ?>
                        <div data-name="<?php echo esc_attr( $name ); ?>" 
                             data-object_name="<?php echo esc_attr( $class_name ); ?>"
                             data-category="<?php echo esc_attr( implode( ',', $cat ) ); ?>"
                             data-type="<?php echo esc_attr( $free_pro ); ?>"
                             class="ua-widget-item <?php echo esc_attr( implode( " ", $html_class ) ); ?>">
                            <div class="ua-widget-item-inside">
                                <span class="ua-widget-version-type ua-widget-version-type-<?php echo esc_attr( $free_pro ); ?>"><?php echo $free_pro == 'pro'? esc_html__( 'Premium', 'ultraaddons' ) : '' ?></span>
                                <i class="ua-widget-icon <?php echo esc_attr( $icon ); ?>"></i>
                                <h2 class="ua-widget-name"><?php echo esc_html( $name ); ?></h2>
                                <div class="ua-widget-checkbox">

                                    <input type="checkbox" name="widget[]" value="<?php echo esc_attr( $class_name ); ?>" <?php echo esc_attr( $checkbox ); ?>>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary" type="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>