<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();

/**
 * List Of Activated Widgets of UltraAddons
 * 
 * Widget has come from Plugin/ultraaddons-elementor-lite/inc/base/widgets_array.php file
 * Controll by Widgets_Manager Object/Class
 * 
 * In that file, The Array's Each Item array formate like bellow:
 * ******************************
 * 'Button'=> [
    'name'  => __( 'Button', 'ultraaddons' ),
    ],
 * ******************************
 * 
 * ### To that Array ####
 * 
 * Array key will be name of Class. and name should be like file name
 * Actually If Aray key: Advance_Title, file name shold be: advance-title.php in widgets folder and advance-title.css in css folder
 * 
 * ****************************
 * and Each $widgets['name'] will be title of the widgets
 * Actually we will handle also it from database.
 * 
 * Controlling All of our Widgets
 * 
 * @since 1.0.0.4
 */
class Widgets_Manager{
    
    /**
     * To save in database, we will use this key
     *
     * @var type Key of get_options()
     */
    public static $key = 'ultraaddons_widgets';

    
    /**
     * To save in database, we will use this key
     *
     * @var type Key of get_options()
     */
    public static $disabled_items_key = 'ultraaddons_disabled_widgets';

    
    /**
     * Retrieve/Get Array of Widgets Item
     * Primarily we will take Widget list from File. But if any user change any name from
     * Admin Panel, then we will take generated name
     * 
     * @access public
     * @author Saiful
     * 
     * @since 1.0.0.5
     * 
     * @return Array|Null if success, array of Widgets otherwise, return null.
     */
    public static function widgets() {
        
        /**
         * File of Widgets Array
         */
        $file = ULTRA_ADDONS_DIR . 'inc/core/list/widgets-array.php';
        
        if( ! is_file( $file ) ){
            return [];
        }
        $widgetsArray = include $file;

        if( is_array( $widgetsArray ) ){
            return $widgetsArray;
        }
        return [];
    }
    
    /**
     * Getting Single Widget args Details
     * of Widget Array list
     * 
     * @param type $widgetKey
     * @return boolean
     */
    public static function getWidget( $widgetKey = false ){
        if( ! $widgetKey ) return false;
        
        $widgets = self::widgets();
        return isset( $widgets[$widgetKey] ) ? $widgets[$widgetKey] : false;
    }

    /**
     * 
     * getting Array of Active Widget
     * 
     * Based on Disabled array's keys.
     * Based on Pro/Free Version.
     * 
     * If Activate pro and and not in disble list,
     * Then user will get that widget in FrontEnd
     * 
     * Such: [ 'Button','Advance_Heading' ];
     * 
     * ** Based on free/pro version - has done at v1.0.7.17
     * 
     * @since 1.0.0.5
     * @access public
     * 
     * @return Array
     */
    public static function activeWidgets(){
        //var_dump(Widgets_Manager::getWidget('Advance_Heading'));
        $widgets = self::widgets();
        $active_widgets = [];
        foreach( $widgets as $widget_key => $widget ){
            $is_pro = isset( $widget['is_pro'] ) ? $widget['is_pro'] : false;
            if( ! in_array( $widget_key, self::disableWidgetKeys() ) && ( ! $is_pro || ultraaddons_is_pro() ) ){
               $active_widgets[$widget_key] = $widget; 
            }
        }
        return apply_filters( 'ultraaddons/widgets/active', $active_widgets );//$active_widgets;
    }
    
    /**
     * Get Pro Widget from whole Widget List
     * 
     * @since 1.0.9.3
     * 
     * @return Array|Null
     */
    public static function get_pro_widgets(){
        
        $widgets = self::widgets();
        $pro_widgets = [];
        foreach( $widgets as $widget_key => $widget ){
            $is_pro = isset( $widget['is_pro'] ) ? $widget['is_pro'] : false;
            if( $is_pro ){
               $pro_widgets[$widget_key] = $widget; 
            }
        }
        return apply_filters( 'ultraaddons/widgets/pro', $pro_widgets );//$pro_widgets;
    }
    
    /**
     * Numeric Array of Pro Widget
     * Get Pro Widget from whole Widget List
     * 
     * @since 1.0.9.3
     * 
     * @return Array|Null
     */
    public static function proWidgets(){
        
        $widgets = self::widgets();
        $pro_widgets = [];
        foreach( $widgets as $widget_key => $widget ){
            $is_pro = isset( $widget['is_pro'] ) ? $widget['is_pro'] : false;
            if( $is_pro ){
                
                //Assign key in array
                $widget['key'] = $widget_key;
               $pro_widgets[] = $widget; 
            }
        }
        return apply_filters( 'ultraaddons/widgets/proWidgets', $pro_widgets );//$pro_widgets;
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
    public static function disableWidgetKeys(){
        $disable_widgets = get_option( self::$disabled_items_key, '' );
        
        if( is_array( $disable_widgets ) && ! empty( $disable_widgets ) ){
            return apply_filters( 'ultraaddons/widgets/disabled', $disable_widgets );
        }
        return [];//Tested with ['Button']
    }
}
