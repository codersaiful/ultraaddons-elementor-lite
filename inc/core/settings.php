<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();

/**
 * Control of Settings
 * Actually we need can some setting for Our Plugin.
 * So we will use this function to Control of Setting from Admin Panel
 * 
 * Primarily we will use to save setting, where will show widget.
 * In Basic Category of Elementor or 
 * UltraAddons Category 
 *
 * @author Saiful
 * @since 1.0.2.1
 * @date 3.2.2021
 */
class Settings {
    
    /**
     * key for update and get data from database.
     *
     * @var string option key for update and get data from database. 
     */
    public static $key = 'ultraaddons_settings';
    
    /**
     * Default data for header id, and footer id.
     *
     * @var Array 
     */
    public static $data = [
        'widget_in' => '',//basic,general
    ];

    
    
    /**
     * Getting settings data from
     * database
     * I have taken data based on sell:$key which is 'ultraaddons_settings'
     * Actually if not found any data in database, based on this key
     * then it will return default data from property
     * 
     * @Hook Available hook is ultraaddons/header_footer/data. can be call from pro version.
     * 
     * @return array|null
     */
    public static function get_data() {
        /**
         * if not found data based on key, then it will return default 
         * data from property
         * 
         * @since 1.0.1.0
         */
        $data = get_option( self::$key, self::$data );
        return apply_filters( 'ultraaddons/settings/data', $data );
    }
    
    /**
     * Retrieve category name for widget, which is saved in database,
     * and store from Setting page of UltraAddons
     * 
     * Elementor Widget's category name slug. Mainly used in Base/Base() and in setting page in admin. Obviously should be String.
     * 
     * *************************
     * Explanation
     * *************************
     * Sometime user 
     * want to show widget in Elementor Basic Category
     * Even can want to show in General Category
     * 
     * @return String Elementor Widget's category name slug. Mainly used in Base/Base() and in setting page in admin. Obviously should be String.
     */
    public static function get_widget_category() {

        $data = self::get_data();
        if( isset( $data['widget_in'] ) && ! empty( $data['widget_in'] ) ){
            return $data['widget_in'];
        }
        return;
    }
    
    /**
     * Setting data for Pro widget
     * It's alias of get_widget_category()
     * 
     * @return type
     */
    public static function get_pro_widget_category() {

        $data = self::get_data();
        if( isset( $data['widget_pro_in'] ) && ! empty( $data['widget_pro_in'] ) ){
            return $data['widget_pro_in'];
        }
        return;
    }
    
    /**
     * Getting single data from database
     * Getting data from save data.
     * 
     * we have used get_options() for this method
     * 
     * @param String $keyword for empty or null, return will null
     * @return String|Array|Null
     * 
     * @since 1.0.1.4
     * @by Saiful
     */
    public static function get_single_data( $keyword = false ){
        if( ! $keyword || ! is_string( $keyword ) || empty( $keyword ) ){
            return;
        }
        
        $data = self::get_data();
        if( isset( $data[$keyword] ) && ! empty( $data[$keyword] ) ){
            return $data[$keyword];
        }
        return;
        
    }
    
    
}
