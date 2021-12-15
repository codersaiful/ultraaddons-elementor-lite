<?php 
namespace UltraAddons\Library\Demo_Manager;

defined('ABSPATH') || die();

class Theme_Demo{
    
    public static $theme_demo_args;


    public static function init():void{
        // var_dump(self::get_demo_args());
    }

    /**
     * Based on this method, We will handle
     *
     * @return array
     */
    public static function get_demo_args():array
    {
        self::$theme_demo_args = [
            'button' => [
                'text'	=> esc_html__( "Theme Demo", 'ultraaddons' ),
                'icon'	=> 'uicon-ultraaddons',
            ],
            'tabs' => [
                'section' => esc_html__( "Blocks", 'ultraaddons' ),
                'page' => esc_html__( "Pages", 'ultraaddons' ),
                'landing' => esc_html__( "Landing", 'ultraaddons' ),
            ],
    
        ];
        return apply_filters( 'eldm_theme_demo_args', self::$theme_demo_args );
    }


}