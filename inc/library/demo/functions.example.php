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
	$demo_manager = new \UltraAddons\Library\Demo\Theme_Demo;
	$args = [
		'root_site' => 'http://wptheme.cm/',
		'button' => [
			'text'	=> esc_html__( "My Demo", 'ultraaddons' ),
			'icon'	=> 'uicon-ultraaddons',
		],
		'tabs' => [
			'section' => esc_html__( "Blocks", 'ultraaddons' ),
			'page' => esc_html__( "Pages", 'ultraaddons' ),
			'landing' => esc_html__( "Landing", 'ultraaddons' ),
		],
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
	 */
	$demo_manager->setRootSite( 'http://wptheme.cm/' );

	$demo_manager->load();


	// var_dump($demo_manager);
}
