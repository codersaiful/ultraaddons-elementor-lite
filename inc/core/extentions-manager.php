<?php
namespace UltraAddons\Core;

/**
 * Extension of UltraAddons Manager
 * 
 * Controlling All of our Extension
 * 
 * @since 1.0.0.4
 */
class Extensions_Manager{
    
    /**
     * To save in database, we will use this key
     *
     * @var type Key of get_options()
     */
    public static $disabled_extenstions_key = 'ultraaddons_disabled_extenstion';

    
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
        $disable_keys = self::disableExtensionKeys();
        
        foreach( self::get_list() as $ex_name_key => $extension ){
            
            $file = ULTRA_ADDONS_DIR . "inc/extensions/{$ex_name_key}.php";
            if( ! in_array( $ex_name_key, $disable_keys ) && is_readable( $file ) ){
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
     * 
     * @todo Ultra Effect is still not completed.
     */
    public static function get_list(){
        return [
            'wrapper-link'=> [
                    'name'  => __( 'Wrapper Link -d', 'ultraaddons' ),
            ],
            
            'hover-effect'=> [
                    'name'  => __( 'Hover Effect -d', 'ultraaddons' ),
            ],
            
            'Wrapper_Link'=> [
                    'name'      => __( 'Wrapper Link', 'ultraaddons' ),
                    'is_free'   => true,
                    'icon'      => 'eicon-button',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],


            'Hover_Effect' => [
                    'name'  => __( 'Hover Effect', 'ultraaddons' ),
                    'is_free'   => true,
                    'icon'      => 'eicon-heading',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],
            
            'Button'=> [
                    'name'      => __( 'Button', 'ultraaddons' ),
                    'is_free'   => true,
                    'icon'      => 'eicon-button',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],


            'Advance_Heading' => [
                    'name'  => __( 'Advance Heading', 'ultraaddons' ),
                    'is_free'   => true,
                    'icon'      => 'eicon-heading',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],

//         Need more Customizer and to be update   
//            'ultra-effects'=> [
//                    'name'  => __( 'Ultra Effect', 'ultraaddons' ),
//            ],
            
        ];
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
    public static function disableExtensionKeys(){
        $disable_extension = get_option( self::$disabled_extenstions_key, '' );
        
        if( is_array( $disable_extension ) && ! empty( $disable_extension ) ){
            return apply_filters( 'ultraaddons/extension/disabled', $disable_extension );
        }
        return [];//Tested with ['Button']
    }
}

Extensions_Manager::init();