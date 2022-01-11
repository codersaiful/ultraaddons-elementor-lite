<?php

use UltraAddons\Core\Widgets_Manager;

defined( 'ABSPATH' ) || die();

$updated = filter_input_array( INPUT_POST );
if( $updated ){
    $update_value = false;
    if( ! empty( $updated['item'] ) ){
        $update_value = $updated['item'];
    }
    update_option( Widgets_Manager::$disabled_items_key, $update_value );
}


$items = Widgets_Manager::widgets();
$items['More'] = [
            'name'      => __( 'More Widget Comming Soon ....', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'uicon-ultraaddons',//eicon-global-colors
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ];
$disable_items = Widgets_Manager::disableWidgetKeys();
?>

<div class="ultraaddons-section ua-option-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1 class="ua-page-title"><?php echo esc_html__( 'Widgets', 'ultraaddons' ); ?></h1>
        </div>

        <div class="category-list">
            <ul class="widget-free-pro-list">
                <li class="wid-cat-wise-target" data-target="free"><?php echo esc_html__( "Free", "ultraaddons" ); ?></li>
                <li class="wid-cat-wise-target" data-target="pro"><?php echo esc_html__( "Premium", "ultraaddons" ); ?></li>
                <li class="wid-cat-wise-target active" data-target="all"><?php echo esc_html__( "All", "ultraaddons" ); ?></li>
            </ul>
            <ul class="widget-cat-list" >
            <?php
            $temp_widgets = $items;
            $wid_cats = [];
            foreach( $temp_widgets as $temp_wid_key => $temp_wdget ){
                $cat = $temp_wdget['cat'][0] ?? 'no-cat';
                $c_name = str_replace( '_', ' ', $cat );
                $wid_cats[$cat] =  $c_name;
            }
            $wid_cats['all'] = esc_html__( 'All', 'ultraaddons' );

            foreach( $wid_cats as $wid_cat_key => $wid_cat ){
                $active_class = $wid_cat_key == 'all' ? 'active' : '';
            ?>
                <li class="wid-cat-wise-target <?php echo esc_attr( $active_class ); ?>" data-target="<?php echo esc_attr( $wid_cat_key ); ?>" ><?php echo $wid_cat; ?></li>
            <?php
            }
            
            ?>
            </ul>

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
                            
                            $checkbox = in_array( $class_name, $disable_items ) ? 'checked' : '';
                            $enbl_disbl_class = in_array( $class_name, $disable_items ) ? 'disabled' : 'enabled';
                            $checkbox_id = 'checkbox_' . $class_name;
                            $html_class = [];
                            //$html_class[] = $name;
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
                    <div class="ua-item-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit" name="submit" value="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>