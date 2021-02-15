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
    
    public static $sub_menu = array();
    public static $capability = ULTRA_ADDONS_CAPABILITY;

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
        
        add_action( 'admin_menu', [ __CLASS__, 'admin_menu' ] );
    }
    
    /**
     * Enqueueing File for Admin Section
     * 
     * @since 1.0.0.5
     */
    public static function get_enqueue(){
        $handle = 'ultraaddons-elementor-admin-style';
        $src = ULTRA_ADDONS_ASSETS . 'css/admin.css';
        $deps = [];
        $ver = ULTRA_ADDONS_VERSION;
        $media = 'all';
        
        wp_register_style( $handle, $src, $deps, $ver, $media );
        wp_enqueue_style( $handle );
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
    
    /**
     * Handle Dashboard Menu for UltraAddons Elementor Plugin
     * Primary Decision, Menu List will be:
     * ******************************
     * # UltraAddons
     * #### Welcome
     * #### Setting
     * #### Widgets
     * #### Extensions
     * #### Faq 
     * ******************************
     * 
     * @access public
     * @author Saiful
     * 
     * return void Displaying menu for User
     */
    public static function admin_menu(){
        $icon_url = ULTRA_ADDONS_ASSETS . 'images/svg-icon/white.svg';
        $menu = [
            'page_title'    => __( 'UltraAddons Elementor Addons', 'ultraaddons' ),
            'menu_title'    => __( 'UltraAddons', 'ultraaddons' ),
            'capability'    => self::$capability,
            'menu_slug'    => 'ultraaddons-elementor-light',
            'function'    => [ __CLASS__, 'root_page' ],
            'icon_url'    => $icon_url,
            'position'    => 45,
        ];
        
        $menu = apply_filters( 'ultraaddons/admon/menu', $menu );
        
        $page_title = isset( $menu['page_title'] ) ? $menu['page_title'] : false;
        $menu_title = isset( $menu['menu_title'] ) ? $menu['menu_title'] : false;
        $capability = 'manage_woocommerce';//isset( $menu['capability'] ) ? $menu['capability'] : false;
        $menu_slug = isset( $menu['menu_slug'] ) ? $menu['menu_slug'] : false;
        $function = isset( $menu['function'] ) ? $menu['function'] : false;
        $icon_url = isset( $menu['icon_url'] ) ? $menu['icon_url'] : false;
        $position = isset( $menu['position'] ) ? $menu['position'] : false;
        
        
        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        
//        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
    }
    
    public static function get_submenu( $parent_slug = false ){
        self::$sub_menu = [
            [
                'parent_slug'   => $parent_slug,
                'page_title'    =>  __( 'Welcome to UltraAddons', 'ultraaddons' ),
                'menu_title'    =>  __( 'Welcome', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-welcome',
                'function'      => 'welcome_page',
                'position'      =>  1,
            ],
        ];
        
        return apply_filters( 'ultraaddons/admin/sub_menu', self::$sub_menu );
    }
    
    public static function root_page() {
        ?>
            
<h1><?php echo esc_html__( 'Welcome to UltraAddons Addons for Elementor Page Builder.' ); ?></h1>    
        <?php
    }
}
Admin_Handle::init();