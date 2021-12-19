<?php 
namespace UltraAddons\Library\Demo;

use UltraAddons\Library\Library_Source;
use Elementor\Plugin;

defined('ABSPATH') || die();

class Theme_Demo{
    
    public $theme_demo_args;

    public function load(){
        //var_dump(self::$get_demo_args());

        var_dump($this->get_demo_info());
        //use Elementor\Plugin;
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
    public function get_demo_info():array
    {
        if( ! is_null( $this->theme_demo_args ) ) return $this->theme_demo_args;



        $default_args = [
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
        $this->theme_demo_args = apply_filters( 'eldm_theme_demo_args', $default_args );
        return $this->theme_demo_args;
    }

    public function set_demo_info( array $args ):object 
    {
        if( ! is_array( $args ) ) return null;
        $this->theme_demo_args = $args;
        return $this;
    }

    
    public function setRootSite( string $root_site_url ):object 
    {
        if( empty( $root_site_url ) || ! is_string( $root_site_url ) ) return null;
        $this->theme_demo_args['root_site'] = $root_site_url;
        return $this;
    }



}