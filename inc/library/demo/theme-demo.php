<?php 
namespace UltraAddons\Library\Demo_Manager;

use UltraAddons\Library\Library_Source;

defined('ABSPATH') || die();

class Theme_Demo{
    const TEMPLATE_ASSETS = ULTRA_ADDONS_URL . 'inc/library/assets/';
    
    public static $theme_demo_args;


    public static function init():void{
        // var_dump(new Theme_Demo);
        // var_dump(self::get_demo_args());

        add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );


        // Additional Enequeue assets
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );

        // Button Style enque
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'button_styles' ] );
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



    
    public static function enqueue_assets() {
		wp_enqueue_style(
			'ultraaddons-library-template',
			self::TEMPLATE_ASSETS . 'css/theme-template-library.min.css',
			[
				'elementor-editor',
			],
			ULTRA_ADDONS_VERSION
		);
	}
    public static function button_styles() {
        wp_enqueue_style(
        'ultraaddons-theme-demo-templt',
        self::TEMPLATE_ASSETS . 'css/custom.css',
        null,
        ULTRA_ADDONS_VERSION
    );
}

    public static function print_template_views() {
        // echo '<saiful>saiful</saiful>';
		include_once __DIR__ . '/templates/theme-template.php';
	}

}