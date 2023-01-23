<?php
/**
 * Plugin Name: UltraAddons - Elementor Addons by CodeAstrology
 * Plugin URI: https://ultraaddons.com/
 * Description: 78+ Fee widget, Custom Font, Custom CSS, Anywhere Elementor Shortcode, Header Footer Builder, Menu Builder, Woo Widget, 85+ Full Ready Template. All are free to use.
 * Version: 1.1.5
 * Author: CodeAstrology Team
 * Author URI: https://codeastrology.com/
 * License: GPL3+
 * License URI: http://www.gnu.org/licenses/gpl.html
 * Text Domain: ultraaddons
 * Domain Path: /languages/
 * 
 * Requires at least:    4.0.0
 * Tested up to:         6.1.1
 * WC requires at least: 3.0.0
 * WC tested up to: 	 7.3.0
 * Elementor tested up to: 3.7.8
 * Elementor Pro tested up to: 7.11.0
 *
 * @package UltraAddons
 * @category Addons
 * 
 * ********************************
 * Used Color Code:
 * #ff00b1 - light of Elementor
 * 
 * ********************************
 * Can Used Color:
 * #607d8b - For Elementor Screen section title background
 * ********************************
 * 
 * UltraAddons - Elementor Addons is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * UltraAddons - Elementor Addons is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * 
 */


defined( 'ABSPATH' ) || die();

define( 'ULTRA_ADDONS_VERSION', '1.1.5.1' );
define( 'ULTRA_ADDONS__FILE__', __FILE__ );
define( 'ULTRA_ADDONS_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'ULTRA_ADDONS_DIR', plugin_dir_path( ULTRA_ADDONS__FILE__ ) );
define( 'ULTRA_ADDONS_URL', plugin_dir_url( ULTRA_ADDONS__FILE__ ) );
define( 'ULTRA_ADDONS_ASSETS', trailingslashit( ULTRA_ADDONS_URL . 'assets' ) );

define( 'ULTRA_ADDONS_WIDGET_HELP_ULR', trailingslashit( 'https://ultraaddons.com/widget/' ) );

define( 'ULTRA_ADDONS_MINIMUM_ELEMENTOR_VERSION', '2.5.0' );
define( 'ULTRA_ADDONS_MINIMUM_PHP_VERSION', '5.4' );


$ultraaddons_capability = apply_filters( 'ultraaddons_capability', 'manage_ultraaddons' );
define( 'ULTRA_ADDONS_CAPABILITY', $ultraaddons_capability );

/**
 * Auto Loader
 * finally activated and running now.
 * 
 * It's was inside UltraAddons Class, but problem on activation loader. I I transferred here.
 * 
 * @since 1.0.1.0
 * @since 1.1.4.5 It's was inside UltraAddons Class, but problem on activation loader. I I transferred here.
 */
include_once ULTRA_ADDONS_DIR . 'autoloader.php';

/**
 * Main ULTRA_ADDONS Addons Class
 *
 * The main class that initiates and runs the plugin.
 * 
 * *****************************
 * * Check Elementor Activated or Not
 * * Check PHP Version
 * * Check Elementor's Version
 * ******************************
 * 
 *@todo admin style method is empty now. Will add style for admin
 * 
 * @since 1.0.0
 * @author Saiful Islam<codersaiful@gmail.com>
 */
final class UltraAddons {
        
	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = ULTRA_ADDONS_VERSION;

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var UltraElementor The single instance of the class.
	 */
	private static $_instance = null;

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

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'ultraaddons' );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		/**
		 * Mainly for check UltraAddons Installed or now.
		 * 
		 * If any user want to make a theme by our plugin,
		 * He/she can check our plugin activation statys
		 * by did_action( 'ultraaddons_init' )
		 */
		do_action( 'ultraaddons_init' );

			
	
		//Including Function File. It will stay at the Top of the File
		include_once ULTRA_ADDONS_DIR . 'inc/functions.php';

                
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

                
		/**
		 * SDK Integration
		 * Actually added first time @since 1.0.5.1
		 * 
		 * @since 1.0.7.15
		 */
		//include_once ULTRA_ADDONS_DIR . 'inc/sdk/integration.php'; //Integration has removed
		
		
		if( is_admin() ){
			
			include_once ULTRA_ADDONS_DIR . 'admin/admin-handle.php';
		}
		
		
		
		//Elementor Widget and script Loader
		include_once ULTRA_ADDONS_DIR . 'loader.php';
        
		/**
		 * Checking UltraAddons Elementor Loaded or not
		 * We added it after 1.1.0.8
		 * and add this hook after Full load UltraAddons
		 * 
		 * @since 1.1.0.8
		 * @author Saiful Islam <codersaiful@gmail.com>
		 */
		do_action( 'ultraaddons_loaded' );
                

	}

        /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '%1$s requires %2$s to be installed and activated.%3$s', 'ultraaddons' ),
			'<strong>' . esc_html__( 'UltraAddons - Elementor Addons', 'ultraaddons' ) . '</strong>',
			'<strong><a href="https://wordpress.org/plugins/elementor/" target="_blank">' . esc_html__( 'Elementor', 'ultraaddons' ) . '</a></strong>',
			'<style>div.ultraaddons-notice-error-elementor{background:#e5e5e5;color:#607d8b}div.ultraaddons-notice-error-elementor>p{font-size:22px}div.ultraaddons-notice-error-elementor>p>strong{color:#9c27b0;font-weight:700}</style>'
		);

		printf( '<div class="notice notice-error ultraaddons-notice-error-elementor"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ultraaddons' ),
			'<strong>' . esc_html__( 'UltraAddons - Elementor Addons', 'ultraaddons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ultraaddons' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ultraaddons' ),
			'<strong>' . esc_html__( 'UltraAddons - Elementor Addons', 'ultraaddons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ultraaddons' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
        
}

UltraAddons::instance();
register_activation_hook( __FILE__, 'ultraaddons_elementor_activation' );

/**
 * Activation Hook.
 * 
 * Our Procedure to this Activation is:
 * ************************************
 * ** Assign Role for manage Permision
 * ** next Process
 * ************************************
 * 
 * @return void Process on Plugin Instalation
 */
function ultraaddons_elementor_activation(){
    
    /**
     * Assigning New Role From UltraAddons Plugin.
     */
    $role = get_role( 'administrator' );
    $role->add_cap( ULTRA_ADDONS_CAPABILITY );
    
    $cpt_support = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );
    $hf_post_type = \UltraAddons\WP\Header_Footer_Post::$post_type; //It's actually 'header_footer'
	if( is_array($cpt_support) ){
        $cpt_support[$hf_post_type] = $hf_post_type;
        update_option( 'elementor_cpt_support', $cpt_support);
    }
}