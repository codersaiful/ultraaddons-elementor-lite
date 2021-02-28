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
     * Sample::
     * **********************************
     * 'fa-regular' => 
    array (size=10)
      'name' => string 'fa-regular' (length=10)
      'label' => string 'Font Awesome - Regular' (length=22)
      'url' => string 'http://wp.cm/wp-content/plugins/elementor/assets/lib/font-awesome/css/regular.min.css' (length=85)
      'enqueue' => 
        array (size=1)
          0 => string 'http://wp.cm/wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.min.css' (length=89)
      'prefix' => string 'fa-' (length=3)
      'displayPrefix' => string 'far' (length=3)
      'labelIcon' => string 'fab fa-font-awesome-alt' (length=23)
      'ver' => string '5.15.1' (length=6)
      'fetchJson' => string 'http://wp.cm/wp-content/plugins/elementor/assets/lib/font-awesome/js/regular.js' (length=79)
      'native' => boolean true
     * **********************************
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
            'nativ'     => false,
        ];
        return $tabs;
    }


}

//Icons_Manager::init();