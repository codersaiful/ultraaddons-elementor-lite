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
    public static $menu_slug = 'ultraaddons-elementor-light';

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
        //$ultraaddons_links[] = '<a href="https://codecanyon.net/item/woo-product-table-pro/20676867" title="' . esc_attr__( 'Many awesome features is waiting for you', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'GET PRO VERSION','ultraaddons_pro' ).'</a>';
        //$ultraaddons_links[] = '<a href="https://codecanyon.net/item/woo-product-table-pro/20676867" title="' . esc_attr__( 'Many awesome features is waiting for you', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'GET PRO VERSION','ultraaddons_pro' ).'</a>';
        $ultraaddons_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'Support','ultraaddons_pro' ).'</a>';
        $ultraaddons_links[] = '<a href="https://github.com/codersaiful/ultraaddons-elementor-lite" title="' . esc_attr__( 'Github Repo Link', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'Github Repository','ultraaddons_pro' ).'</a>';
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
            'menu_slug'    => self::$menu_slug,//'ultraaddons-elementor-light',
            'function'    => [ __CLASS__, 'root_page' ],
            'icon_url'    => $icon_url,
            'position'    => 45,
        ];
        
        $menu = apply_filters( 'ultraaddons/admon/menu', $menu );
        
        $page_title = isset( $menu['page_title'] ) ? $menu['page_title'] : false;
        $menu_title = isset( $menu['menu_title'] ) ? $menu['menu_title'] : false;
        $capability = isset( $menu['capability'] ) ? $menu['capability'] : false; //'manage_woocommerce';//
        $menu_slug = isset( $menu['menu_slug'] ) ? $menu['menu_slug'] : false;
        $function = isset( $menu['function'] ) ? $menu['function'] : false;
        $icon_url = isset( $menu['icon_url'] ) ? $menu['icon_url'] : false;
        $position = isset( $menu['position'] ) ? $menu['position'] : false;
        
        
        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        
        /**
         * Adding Submenu
         */
        self::add_submenu( $menu_slug );
    }
    
    /**
     * Add Submenu
     * Submenu 
     * 
     * @return void Adding Submenuj
     */
    public static function add_submenu( $parent_slug = false ) {
        foreach( self::get_submenu() as $menu ){

            $parent_slug = isset( $menu['parent_slug'] ) ? $menu['parent_slug'] : false;
            $page_title = isset( $menu['page_title'] ) ? $menu['page_title'] : false;
            $menu_title = isset( $menu['menu_title'] ) ? $menu['menu_title'] : false;
            $capability = isset( $menu['capability'] ) ? $menu['capability'] : false;
            $menu_slug = isset( $menu['menu_slug'] ) ? $menu['menu_slug'] : false;
            $function = isset( $menu['function'] ) ? $menu['function'] : false;
            $icon_url = isset( $menu['icon_url'] ) ? $menu['icon_url'] : false;
            $position = isset( $menu['position'] ) ? $menu['position'] : false;

            add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
        }
    }
    
    /**
     * Get Array of Submenu
     * with title,menu title, capability
     * slug,function etc.
     * 
     * @param string $parent_slug
     * @return array
     */
    public static function get_submenu(){
        self::$sub_menu = [
            [
                'parent_slug'   => self::$menu_slug,//$parent_slug,
                'page_title'    =>  __( 'UltraAddons Widgets', 'ultraaddons' ),
                'menu_title'    =>  __( 'Widgets', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-widgets',
                'function'      => [__CLASS__, 'widgets_page'],
                'position'      =>  1,
            ],
            
            [
                'parent_slug'   => self::$menu_slug,//$parent_slug,
                'page_title'    =>  __( 'UltraAddons Extensions', 'ultraaddons' ),
                'menu_title'    =>  __( 'Extensions', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-extensions',
                'function'      => [__CLASS__, 'extensions_page'],
                'position'      =>  2,
            ],
            
        ];
        
        self::$sub_menu = apply_filters( 'ultraaddons/admin/sub_menu', self::$sub_menu );
        
        if( empty( self::$sub_menu ) || ! is_array( self::$sub_menu ) ){
            self::$sub_menu = [];
        }
        
        return self::$sub_menu;
    }
    
    /**
     * Opening Welcome Page for User.
     */
    public static function root_page() {
        include ULTRA_ADDONS_DIR . 'admin/pages/main.php';
    }
    
    
    /**
     * Opening Widget User.
     */
    public static function widgets_page() {
        include ULTRA_ADDONS_DIR . 'admin/pages/widgets.php';
    }
    
    
    /**
     * Opening Extension for User.
     */
    public static function extensions_page() {
        include ULTRA_ADDONS_DIR . 'admin/pages/extensions.php';
    }
    
    
}
Admin_Handle::init();