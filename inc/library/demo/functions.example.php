<?php
/**
 * It's a example file
 * 
 * COPY BELLOW CODE AND PASTE TO YOUR FUNCTIONS.PHP
 */

if( class_exists( 'ELDM_Elementor_Demo_Manager' ) ){

    /**
     * Ekhane emon kichu bebostha korote hobe
     * jeno ekhan kar code niye demo dekhay
     * 
     * 
     */
}

//http://wptheme.cm/wp-json/demo/v2/templates
if( class_exists( '\UltraAddons\Library\Demo\Theme_Demo' ) ){
	//$demo_manager = new \UltraAddons\Library\Demo\Theme_Demo;
	$args = [
		'root_site' => 'http://localhost/wordpress_theme/',
		'button' => [
			'text'	=> esc_html__( "Theme Demo", 'ultraaddons-elementor-lite' ),
			'icon'	=> 'eicon-instagram-likes',
			'position'=> 99
		],
		'tabs' => [
			'section' => esc_html__( "Blocks", 'ultraaddons-elementor-lite' ),
			'page' => esc_html__( "Pages", 'ultraaddons-elementor-lite' ),
			'landing' => esc_html__( "Landing", 'ultraaddons-elementor-lite' ),
		],
		'library_icon'      => 'eicon-gallery-grid',
        'library_title'     => esc_html__( "THEME DEMOS", 'ultraaddons-elementor-lite' ),
		'back_button_text' => esc_html__( 'Back to All', 'ultraaddons-elementor-lite' ),
		'lern_more_message' => esc_html__( 'Learn more about our Theme Template.', 'ultraaddons-elementor-lite' ),
		'page_templates' => 'https://ultraaddons.com//',
	];

	/************************
	//Smart Way is this
	$demo_manager
	->set_demo_info($args)
	->load();
	//**********************/

	/**
	 * Short Way
	 * 
	 ************************************
	$demo_manager->setRootSite( 'http://localhost/wordpress_theme/' );
	//********************************/
	//$demo_manager->load();


	// var_dump($demo_manager);
	$demo = new \UltraAddons\Library\Demo\Theme_Demo;
	$demo->set_demo_info( $args );
	// $demo->setRootSite( 'http://localhost/wordpress_theme/' );
	$demo->load();
	// \UltraAddons\Library\Demo\Theme_Demo::set_demo_info($args);
	// \UltraAddons\Library\Demo\Theme_Demo::load();

}