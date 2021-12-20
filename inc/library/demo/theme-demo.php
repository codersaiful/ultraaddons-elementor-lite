<?php 
namespace UltraAddons\Library\Demo;

use UltraAddons\Library\Demo_Library_Source;
use Elementor\Plugin;

defined('ABSPATH') || die();

class Theme_Demo{

    public static $permalink_prefix = 'library';
    public static $templates_permalink = 'wp-json/%s/v2/templates';
    public static $template_permalink = 'wp-json/%s/v2/template/';
    private static $default_root_site = 'https://library.ultraaddons.com/';
    public static $root_site;
    
    public static $theme_demo_args;

    /**
	 * Instance for Main Class
	 * Called from actiovation instance
	 *
	 * @var Object
	 */
    public static $_instance;

    /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return UltraElementor An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

    public static function load(){
        //var_dump(self::$get_demo_args());

        // var_dump(self::$get_demo_info());
        //use Elementor\Plugin;
        Demo_Library_Manager::init();
    }
    


    
    /**
     * Based on this method, We will handle
     *
     * @return array
     */
    public static function get_demo_info():array
    {
        
        if( ! is_array( self::$theme_demo_args ) ){
            self::$theme_demo_args = [];
        } 



        $default_args = [
            'root_site' => self::$default_root_site,
            'button' => [
                'text'	=> esc_html__( "Theme Demo", 'ultraaddons' ),
                'icon'	=> 'uicon-ultraaddons',
            ],
            'tabs' => [
                'section' => esc_html__( "Blocks", 'ultraaddons' ),
                'page' => esc_html__( "Pages", 'ultraaddons' ),
                'landing' => esc_html__( "Landing", 'ultraaddons' ),
            ],
            'library_icon'      => 'eicon-gallery-grid',
            'library_title'     => esc_html__( "THEME DEMOS", 'ultraaddons' ),
            'back_button_text' => esc_html__( 'Back to Library', 'ultraaddons' ),
            'lern_more_message' => esc_html__( 'Learn more about UltraAddons Template Library.', 'ultraaddons' ),
            'page_templates' => 'https://ultraaddons.com/page-templates/',

        ];
        $merrged_args = wp_parse_args( self::$theme_demo_args, $default_args );

        //Fixing root_site
        if( empty( $merrged_args['root_site'] ) ){
            $merrged_args['root_site'] = self::$default_root_site;
        }
        self::$root_site = $merrged_args['root_site'];
        $merrged_args['permalink'] = self::$permalink_prefix;
        $merrged_args['templates'] = self::$root_site . sprintf( self::$templates_permalink, self::$permalink_prefix );
        $merrged_args['template'] = self::$root_site . sprintf( self::$template_permalink, self::$permalink_prefix );

        self::$theme_demo_args = apply_filters( 'eldm_theme_demo_args', $merrged_args );
        return self::$theme_demo_args;
    }

    public static function set_demo_info( array $args ) 
    {
        if( ! is_array( $args ) ) return null;
        self::$theme_demo_args = $args;
        return self::$_instance;
    }

    
    public static function setRootSite( string $root_site_url ) 
    {
        if( empty( $root_site_url ) || ! is_string( $root_site_url ) ) return null;
        self::$theme_demo_args['root_site'] = $root_site_url;
        return self::$_instance;
    }



}