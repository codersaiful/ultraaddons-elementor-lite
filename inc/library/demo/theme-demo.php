<?php 
namespace UltraAddons\Library\Demo;

use UltraAddons\Library\Library_Source;
use Elementor\Plugin;

defined('ABSPATH') || die();

class Theme_Demo{

    public $permalink_prefix = 'library';
    public $templates_permalink = 'wp-json/%s/v2/templates';
    public $template_permalink = 'wp-json/%s/v2/template/';
    private $default_root_site = 'https://library.ultraaddons.com/';
    public $root_site;
    
    public $theme_demo_args;

    public function load(){
        //var_dump(self::$get_demo_args());

        //var_dump($this->get_demo_info());
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
        
        if( ! is_array( $this->theme_demo_args ) ){
            $this->theme_demo_args = [];
        } 



        $default_args = [
            'root_site' => $this->default_root_site,
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
        $merrged_args = wp_parse_args( $this->theme_demo_args, $default_args );

        //Fixing root_site
        if( empty( $merrged_args['root_site'] ) ){
            $merrged_args['root_site'] = $this->default_root_site;
        }
        $this->root_site = $merrged_args['root_site'];
        $merrged_args['permalink'] = $this->permalink_prefix;
        $merrged_args['templates'] = $this->root_site . sprintf( $this->templates_permalink, $this->permalink_prefix );
        $merrged_args['template'] = $this->root_site . sprintf( $this->template_permalink, $this->permalink_prefix );

        $this->theme_demo_args = apply_filters( 'eldm_theme_demo_args', $merrged_args );
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