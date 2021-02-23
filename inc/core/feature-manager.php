<?php

namespace UltraAddons\Core;

/**
 * Description of manager
 *
 * @author CodeOffice
 */
abstract class Feature_Manager {
    public static $feature_name;

    abstract public static function getItems();
    
    /**
     * getting Array of Active Widget
     * Based on Disabled array's keys.
     * Such: [ 'Button','Advance_Heading' ];
     * 
     * @since 1.0.0.5
     * @access public
     * 
     * @return Array
     */
    public static function activeItmes(){
        $active_items = [];
        foreach( self::getItems() as $item_keyword => $single_data ){
            if( ! in_array( $item_keyword, self::disabledKeys() ) ){
               $active_items[$item_keyword] = $single_data; 
            }
        }
        return apply_filters( 'ultraaddons/widgets/active', $active_items );//$active_items;
    }

    /**
     * Getting Disabled Widgets
     * We will save/store in Database by 
     * using wp function update_option()
     * from setting page.
     * Here will store Disabled Widget's key as array
     * 
     * @since 1.0.0.5
     * @access public
     * 
     * @return Array Disabled Widget key's array 
     */
    public static function disabledKeys(){
        $disable_items = get_option( self::$disabled_items_key, '' );
        
        if( is_array( $disable_items ) && ! empty( $disable_items ) ){
            return apply_filters( 'ultraaddons/widgets/disabled', $disable_items );
        }
        return [];//Tested with ['Button']
    }
}
