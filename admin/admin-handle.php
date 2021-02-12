<?php
namespace UltraAddons\Admin;

defined( 'ABSPATH' ) || die();

/**
 * Full Admin are controlled from this Admin_Handle class.
 * 
 * Mainly Setting page, configurate page of dashboard,
 * including css file for admin, including js file from admin
 * etc all mater will handle from here
 * 
 * @since 1.0.0.5
 * @package UltraAddons
 * @author Saiful Islam<codersaiful@gmail.com>
 */
class Admin_Handle{
    
    /**
     * Initialize Full class from here.
     * 
     * @hooked admin_enqueue_scripts
     * @hooked plugin_action_links_[base name]
     * 
     * @since 1.0.0.5
     */
    public static function init() {
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'get_enqueue' ] );
        add_filter('plugin_action_links_' . ULTRA_ADDONS_BASE_NAME, [ __CLASS__, 'add_action_links' ] );
    }
    
    /**
     * Enqueueing File for Admin Section
     * 
     * @since 1.0.0.5
     */
    public static function get_enqueue(){
        wp_register_style( 'ultraaddons-elementor-admin-style', ULTRA_ADDONS_ASSETS . 'css/admin.css' );
        wp_enqueue_style( 'ultraaddons-elementor-admin-style' );
    }
    
    /**
     * For showing configure or add new link on plugin page
     * It was actually an individual file, now combine
     * 
     * @access public
     * 
     * @param type $links
     * @return Array
     */
    public static function add_action_links( $links ) {
        $ultraaddons_links[] = '<a href="https://codecanyon.net/item/woo-product-table-pro/20676867" title="' . esc_attr__( 'Many awesome features is waiting for you', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'GET PRO VERSION','ultraaddons_pro' ).'</a>';
        $ultraaddons_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'Support','ultraaddons_pro' ).'</a>';
        $ultraaddons_links[] = '<a href="https://github.com/codersaiful/woo-product-table" title="' . esc_attr__( 'Github Repo Link', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'Github Repository','ultraaddons_pro' ).'</a>';
        return array_merge( $ultraaddons_links, $links );
    } 
}
Admin_Handle::init();