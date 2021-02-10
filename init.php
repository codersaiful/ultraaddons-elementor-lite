<?php
/**
 * Plugin Name: Addons - UltraAddons Elementor Lite
 * Plugin URI: https://ultraelementor.com/
 * Description: This is Elementor Addons Plugin. This is Elementor Addons Plugin. This is Elementor Addons Plugin.
 * Version: 1.0.0
 * Author: codersaiful
 * Author URI: https://ultraelementor.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ultraaddons
 * Domain Path: /languages/
 *
 * @package UltraAddons
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019 HappyMonster <http://happymonster.me>
*/

defined( 'ABSPATH' ) || die();

define( 'ULTRA_ADDONS_VERSION', '1.0.0.1' );
define( 'ULTRA_ADDONS__FILE__', __FILE__ );
define( 'ULTRA_ADDONS_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'ULTRA_ADDONS_DIR', plugin_dir_path( ULTRA_ADDONS__FILE__ ) );
define( 'ULTRA_ADDONS_URL', plugin_dir_url( ULTRA_ADDONS__FILE__ ) );
define( 'ULTRA_ADDONS_ASSETS', trailingslashit( ULTRA_ADDONS_URL . 'assets' ) );


define( 'ULTRA_ADDONS_MINIMUM_ELEMENTOR_VERSION', '2.5.0' );
define( 'ULTRA_ADDONS_MINIMUM_PHP_VERSION', '5.4' );

//var_dump(ULTRA_ADDONS_DIR,ULTRA_ADDONS_URL,ULTRA_ADDONS_ASSETS);

/**
 * Main ULTRA_ADDONS Addons Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
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

		load_plugin_textdomain( 'ultra-elementor' );

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
            
                //Including Function File. It will stay at the Top of the File
                include_once ULTRA_ADDONS_DIR . 'inc/functions.php';
                
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
                    
                    include_once ULTRA_ADDONS_DIR . 'admin/menu_plugin_setting_link.php'; //Menu/link show in Plugin Name
                    include_once ULTRA_ADDONS_DIR . 'admin/enqueue.php'; //Menu/link show in Plugin Name
                }
                
                //var_dump(ultraaddons_elementor());
                
                
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
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ultra-elementor' ),
			'<strong>' . esc_html__( 'Ultra Elementor Addons', 'ultra-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ultra-elementor' ) . '</strong>'
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
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ultra-elementor' ),
			'<strong>' . esc_html__( 'Ultra Elementor Addons', 'ultra-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ultra-elementor' ) . '</strong>',
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
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ultra-elementor' ),
			'<strong>' . esc_html__( 'Ultra Elementor Addons', 'ultra-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ultra-elementor' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

        public function admin_style() {
            
        }
        
}

UltraAddons::instance();


//
///**
// * The journey of a thousand miles starts here.
// *
// * @return void Some voids are not really void, you have to explore to figure out why not!
// */
//function ha_let_the_journey_begin() {
//    require( ULTRA_ADDONS_DIR . 'inc/functions.php' );
//
//    // Check for required PHP version
//    if ( version_compare( PHP_VERSION, ULTRA_ADDONS_MINIMUM_PHP_VERSION, '<' ) ) {
//        add_action( 'admin_notices', 'ha_required_php_version_missing_notice' );
//        return;
//    }
//
//    // Check if Elementor installed and activated
//    if ( ! did_action( 'elementor/loaded' ) ) {
//        add_action( 'admin_notices', 'ha_elementor_missing_notice' );
//        return;
//    }
//
//    // Check for required Elementor version
//    if ( ! version_compare( ELEMENTOR_VERSION, ULTRA_ADDONS_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
//        add_action( 'admin_notices', 'ha_required_elementor_version_missing_notice' );
//        return;
//    }
//
//    require ULTRA_ADDONS_DIR . 'base.php';
//    \Happy_Addons\Elementor\Base::instance();
//}
//
//add_action( 'plugins_loaded', 'ha_let_the_journey_begin' );
//
///**
// * Admin notice for required php version
// *
// * @return void
// */
//function ha_required_php_version_missing_notice() {
//    $notice = sprintf(
//        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
//        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-elementor-addons' ),
//        '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
//        '<strong>' . esc_html__( 'PHP', 'happy-elementor-addons' ) . '</strong>',
//        ULTRA_ADDONS_MINIMUM_PHP_VERSION
//    );
//
//    printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
//}
//
///**
// * Admin notice for elementor if missing
// *
// * @return void
// */
//function ha_elementor_missing_notice() {
//    $notice = ha_kses_intermediate( sprintf(
//        /* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
//        __( '%1$s requires %2$s to be installed and activated to function properly. %3$s', 'happy-elementor-addons' ),
//        '<strong>' . __( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
//        '<strong>' . __( 'Elementor', 'happy-elementor-addons' ) . '</strong>',
//        '<a href="' . esc_url( admin_url( 'plugin-install.php?s=Elementor&tab=search&type=term' ) ) . '">' . __( 'Please click on this link and install Elementor', 'happy-elementor-addons' ) . '</a>'
//    ) );
//
//    printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
//}
//
///**
// * Admin notice for required elementor version
// *
// * @return void
// */
//function ha_required_elementor_version_missing_notice() {
//    $notice = sprintf(
//        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
//        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-elementor-addons' ),
//        '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
//        '<strong>' . esc_html__( 'Elementor', 'happy-elementor-addons' ) . '</strong>',
//        ULTRA_ADDONS_MINIMUM_ELEMENTOR_VERSION
//    );
//
//    printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
//}
//
///**
// * Register actions that should run on activation
// *
// * @return void
// */
//function ha_register_activation_hook() {
//	add_option( ULTRA_ADDONS_REDIRECTION_FLAG, true );
//}
//
//register_activation_hook( ULTRA_ADDONS__FILE__, 'ha_register_activation_hook' );
