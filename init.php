<?php
/**
 * Plugin Name: Addons - UltraAddons Elementor Lite
 * Plugin URI: https://ultraaddons.com/
 * Description: Elementor Addons Plugin. Build your desired page just few click. Easy to use and useable for any theme and plugin. Available many filter.
 * Version: 1.0.7
 * Author: codersaiful
 * Author URI: https://profiles.wordpress.org/codersaiful/#content-plugins
 * License: GPL3+
 * License URI: http://www.gnu.org/licenses/gpl.html
 * Text Domain: ultraaddons
 * Domain Path: /languages/
 * 
 * Requires at least:    4.0.0
 * Tested up to:         5.7.2
 * WC requires at least: 3.0.0
 * WC tested up to: 	 5.4.1
 * Elementor tested up to: 3.2.5
 * Elementor Pro tested up to: 5.11.0
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
 * UltraAddons Elementor Lite is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * UltraAddons Elementor Lite is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 */


defined( 'ABSPATH' ) || die();

define( 'ULTRA_ADDONS_VERSION', '1.0.7.9' );
define( 'ULTRA_ADDONS__FILE__', __FILE__ );
define( 'ULTRA_ADDONS_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'ULTRA_ADDONS_DIR', plugin_dir_path( ULTRA_ADDONS__FILE__ ) );
define( 'ULTRA_ADDONS_URL', plugin_dir_url( ULTRA_ADDONS__FILE__ ) );
define( 'ULTRA_ADDONS_ASSETS', trailingslashit( ULTRA_ADDONS_URL . 'assets' ) );

define( 'ULTRA_ADDONS_WIDGET_HELP_ULR', trailingslashit( 'https://ultraaddons.com/widgets/' ) );

define( 'ULTRA_ADDONS_MINIMUM_ELEMENTOR_VERSION', '2.5.0' );
define( 'ULTRA_ADDONS_MINIMUM_PHP_VERSION', '5.4' );

$ultraaddons_capability = apply_filters( 'ultraaddons_capability', 'manage_ultraaddons' );
define( 'ULTRA_ADDONS_CAPABILITY', $ultraaddons_capability );

define( 'ULTRA_ADDONS_TEMPLATE_ASSETS', ULTRA_ADDONS_URL . 'inc/library/assets/' );

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
	const VERSION = '1.0.0';

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
                /**
                 * Auto Loader
                 * finally activated and running now.
                 * 
                 * @since 1.0.1.0
                 */
                 include_once ULTRA_ADDONS_DIR . 'autoloader.php';
            
            
                //Including Function File. It will stay at the Top of the File
                include_once ULTRA_ADDONS_DIR . 'inc/functions.php';
                //Handleling Widgets
                //include_once ULTRA_ADDONS_DIR . 'inc/core/widgets-manager.php';
                
                add_action('admin_enqueue_scripts', [$this,'admin_style']);
                
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

                if( is_admin() ){
                    
                    include_once ULTRA_ADDONS_DIR . 'admin/admin-handle.php';
                }
                
                
                
                //Elementor Widget and script Loader
                include_once ULTRA_ADDONS_DIR . 'loader.php';
                
                

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
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ultraaddons' ),
			'<strong>' . esc_html__( 'UltraAddons Elementor Lite', 'ultraaddons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ultraaddons' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

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
			'<strong>' . esc_html__( 'UltraAddons Elementor Lite', 'ultraaddons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ultraaddons' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

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
			'<strong>' . esc_html__( 'UltraAddons Elementor Lite', 'ultraaddons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ultraaddons' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

        public function admin_style() {
            
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
    if( is_array($cpt_support) ){
        $cpt_support['header_footer'] = 'header_footer';
        update_option( 'elementor_cpt_support', $cpt_support);
    }
}