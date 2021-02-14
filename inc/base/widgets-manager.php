<?php
namespace UltraAddons\Base;

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
        $file = ULTRA_ADDONS_DIR . 'inc/base/widgets_array.php';
        
        if( ! is_file( $file ) ){
            return;
        }
        $widgetsArray = include $file;

        if( is_array( $widgetsArray ) ){
            return $widgetsArray;
        }
        return;
    }
    
}
