<?php
namespace UltraAddons\Core;

/**
 * Extension of UltraAddons Manager
 * 
 * ************************************
 * extension manager is completed and but not completed to show placeholder
 * *************************************
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
    public static $disabled_items_key = 'ultraaddons_disabled_extenstion';

    
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

            $file_name = strtolower( str_replace( '_', '-', $ex_name_key ) );
            $file = ULTRA_ADDONS_DIR . "inc/extensions/{$file_name}.php";
            $file = realpath( $file );
            
            //Check pro Extension
            $is_pro = isset( $extension['is_pro'] ) ? $extension['is_pro'] : false;

            /**
             * If Extension in Disable list, 
             * then Extension will not activate
             * 
             * @since 1.0.1 based on enable disable list
             * 
             * and If Extension is pro and not 
             * actimated UltraAddons pro,
             * then Extension will not activated.
             * 
             * @since 1.0.7 based on pree pro version.
             */
            if( ! in_array( $ex_name_key, $disable_keys ) && is_readable( $file ) && ( ! $is_pro || ultraaddons_is_pro() ) ){
                include_once $file;
                $class_name = '\UltraAddons\Extensions\\' . $ex_name_key;
                if( method_exists( $class_name, 'init' ) ){
                    $class_name::init();
                }
            }
//            elseif( $is_pro && ! ultraaddons_is_pro() ){
//                $extension_name = $extension['name'];
//                
//                //\UltraAddons\Extensions\Placeholder_Extension::init( $extension_name );
//                \UltraAddons\Extensions\Placeholder_Extension::init( $extension_name );
//            }
        }
//        \UltraAddons\Extensions\Placeholder_Extension::init( 'Wrapper Link' );
//        \UltraAddons\Extensions\Placeholder_Extension::init( 'CSS Transform' );
//        \UltraAddons\Extensions\Placeholder_Extension::init( 'Animation Effect' );
//        \UltraAddons\Extensions\Placeholder_Extension::init( 'Ultra Effect' );
//        \UltraAddons\Extensions\Placeholder_Extension::init( 'Transform' );
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
            'Wrapper_Link'=> [
                    'name'      => __( 'Wrapper Link', 'ultraaddons' ),
                    'is_pro'   => false,
                    'icon'      => 'uicon-button',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],


            'Hover_Effect' => [
                    'name'  => __( 'Hover Effect', 'ultraaddons' ),
                    'is_pro'   => false,
                    'icon'      => 'uicon-hover',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],
            
            /**
             * CSS Transform Editing for
             * Any content/any element/
             * We will added this Extenstion at V1.0.3.0
             * 
             * @date 4.3.2021
             * @since 1.0.3.0
             */
            'Transform' => [
                    'name'  => __( 'CSS Transform', 'ultraaddons' ),
                    'is_pro'   => true,
                    'icon'      => 'eicon-heading',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],
            

            'Animation_Effect' => [
                    'name'  => __( 'Animation Effect', 'ultraaddons' ),
                    'is_pro'   => true,
                    'icon'      => 'eicon-code-highlight',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],

            'Background_Overlay' => [
                    'name'  => __( 'Background Overlay', 'ultraaddons' ),
                    'is_pro'   => false,
                    'icon'      => 'eicon-background',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons' ),
                    ],
            ],

            'Ultra_Effect' => [
                    'name'  => __( 'Ultra Effect', 'ultraaddons' ),
                    'is_pro'   => true,
                    'icon'      => 'eicon-spinner',
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
        $disable_extension = get_option( self::$disabled_items_key, '' );
        
        if( is_array( $disable_extension ) && ! empty( $disable_extension ) ){
            return apply_filters( 'ultraaddons/extension/disabled', $disable_extension );
        }
        return [];//Tested with ['Button']
    }
}

//Extensions_Manager::init();