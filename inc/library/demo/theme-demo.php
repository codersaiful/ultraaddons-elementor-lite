<?php 
namespace UltraAddons\Library\Demo;

use UltraAddons\Library\Library_Source;

defined('ABSPATH') || die();

class Theme_Demo{
    
    public $theme_demo_args;

    public function load(){
        var_dump($this->get_demo_args());
        Library_Manager::init();
    }
    
    public static function init(){
        
        //Library_Manager::init();
    }

    
    /**
     * Based on this method, We will handle
     *
     * @return array
     */
    public function get_demo_args():array
    {
        $this->theme_demo_args = [
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
        return apply_filters( 'eldm_theme_demo_args', $this->theme_demo_args );
    }
}