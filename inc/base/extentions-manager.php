<?php
namespace UltraAddons\Base;

class Extensions_Manager{
    
    public static function init(){
        $file = ULTRA_ADDONS_DIR . 'inc/extensions/test.php';
        if( ! is_file( $file ) ) return;
        include_once $file;
    }
    
    public static function get_list(){
        return [
            'test'=> [
                    'name'  => 'Test',
            ],
        ];
    }
    
}

Extensions_Manager::init();