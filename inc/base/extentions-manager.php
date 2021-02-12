<?php
namespace UltraAddons\Base;

/**
 * Extension of UltraAddons Manager
 * 
 * Controlling All of our Extension
 * 
 * @since 1.0.0.4
 */
class Extensions_Manager{
    
    /**
     * Starting Including Extensions files from Array
     * and do something for Extension
     * from here
     * 
     * @access public
     * @author Saiful
     * 
     * @todo Will handle and load/include file based on Pro/Active-Deactive. data will come from Database.
     * 
     * @return void
     */
    public static function init(){
        
        //Get supported extension array
        $supported_keys = self::supported_extension_keys();
        
        foreach( self::get_list() as $ex_name_key => $extension ){
            
            $file = ULTRA_ADDONS_DIR . "inc/extensions/{$ex_name_key}.php";
            if( in_array( $ex_name_key, $supported_keys ) && is_readable( $file ) ){
                include_once $file;
            }
        }
    }
    
    /**
     * Retrieve Array of Keys of Supported Extension List
     * Extension will enable/disable from setting page and will change main array
     * 
     * Obviously Always should be an Array Even empty
     * 
     * @access public
     * 
     * @todo Extension will enable/disable from setting page and will change main array. Obviously should be an Array Even empty
     * 
     * @return array Only Keys of Supported Extensions list
     */
    public static function supported_extension_keys() {
        return array_keys( self::get_list() );
    }
    
    /**
     * Retrieve Array of Supported Extension
     * 
     * @access public
     * 
     * @return array List of Supported Extension, Which will be included in extensions folder
     */
    public static function get_list(){
        return [
            'wrapper-link'=> [
                    'name'  => __( 'Wrapper Link', 'ultraaddons' ),
            ],
            
            'hover-effect'=> [
                    'name'  => __( 'Hover Effect', 'ultraaddons' ),
            ],
            
            'ultra-effects'=> [
                    'name'  => __( 'Ultra Effect', 'ultraaddons' ),
            ],
            
        ];
    }
    
}

Extensions_Manager::init();