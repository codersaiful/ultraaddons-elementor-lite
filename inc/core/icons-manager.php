<?php
namespace UltraAddons\Core;

/**
 * Icons_Manager of UltraAddons Manager
 * 
 * Controlling All of our Extension
 * 
 * @since 1.0.2.1
 */
class Icons_Manager{
    
    /**
     * Manage our Custom Icon
     * We will show our own all Icon to the 
     * Icon list
     * 
     * @since 1.0.2.1
     */
    public static function init(){
        add_filter( 'elementor/icons_manager/native', [__CLASS__, 'additional_tab'], 999 );

    }
    
    /**
     * Adding Icons Array, where we will 
     * add our new icon.
     * there is no prefix for that icon
     * 
     * we have created our icon
     * Taking help from Elementor Already used Array. 
     */
    public static function additional_tab( $tabs ) {
        $tabs['ultraaddons'] = [
            'name'      => 'ultraaddons',
            'label'     =>  'UltraAddons Icons',
            'url'       => ULTRA_ADDONS_ASSETS . 'icons/ultraaddons/css/ultraaddons.css',
            'enqueue'   => [
                ULTRA_ADDONS_ASSETS . 'icons/ultraaddons/css/ultraaddons.css',
            ],
            'labelIcon' => 'uicon-ultraaddons',
            'displayPrefix'=> 'uicon',
            'prefix'    => 'uicon-',
            'ver'       => ULTRA_ADDONS_VERSION,
            'fetchJson' => ULTRA_ADDONS_ASSETS . 'icons/ultraaddons/icon-list.js',//http://wp.cm/wp-content/plugins/elementor/assets/lib/font-awesome/js/regular.js
            'native'     => false,
        ];
        return $tabs;
    }


}

//Icons_Manager::init();