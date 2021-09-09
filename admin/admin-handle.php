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
    public static $menu_slug = 'ultraaddons-elementor-lite';
    public static $header_file = ULTRA_ADDONS_DIR . 'admin/pages/includes/admin-header.php';
    public static $footer_file = ULTRA_ADDONS_DIR . 'admin/pages/includes/admin-footer.php';

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
        $handle = 'ultraaddons-admin-style';
        $src = ULTRA_ADDONS_ASSETS . 'css/admin.css';
        $deps = [];
        $ver = ULTRA_ADDONS_VERSION;
        $media = 'all';
        
        wp_register_style( $handle, $src, $deps, $ver, $media );
        wp_enqueue_style( $handle );
        
        $handle = 'ultraaddons-admin-script';
        $src = ULTRA_ADDONS_ASSETS . 'js/admin.js';
        $deps = ['jquery'];
        $ver = ULTRA_ADDONS_VERSION;
        $in_footer = true;
        
        wp_register_script($handle, $src, $deps, $ver, $in_footer);
        wp_enqueue_script($handle);
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
        $ultraaddons_links[] = '<a href="' . admin_url( 'admin.php?page=ultraaddons-elementor-lite' ) . '" title="' . esc_attr__( 'Welcome to UltraAddons', 'ultraaddons' ) . '" target="_blank">' . esc_html__( 'Welcome','ultraaddons' ).'</a>';
        $ultraaddons_links[] = '<a href="https://codecanyon.net/item/ultraaddons-elementor-lite-pro/33337985?ref=CodeAstrology&utm_source=UltraAddons_Installed_Plugin" title="' . esc_attr__( 'GET PRO', 'ultraaddons' ) . '" target="_blank">' . esc_html__( 'GET PRO','ultraaddons' ).'</a>';
        $ultraaddons_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ultraaddons' ) . '" target="_blank">'.esc_html__( 'Support','ultraaddons' ).'</a>';
        //$ultraaddons_links[] = '<a href="https://github.com/codersaiful/ultraaddons-elementor-lite" title="' . esc_attr__( 'Github Repo Link', 'ultraaddons' ) . '" target="_blank">'.esc_html__( 'Github Repository','ultraaddons' ).'</a>';
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
        $icon_url = ULTRA_ADDONS_ASSETS . 'images/white.png';
        $menu = [
            'page_title'    => __( 'UltraAddons Elementor Addons', 'ultraaddons' ),
            'menu_title'    => __( 'UltraAddons', 'ultraaddons' ),
            'capability'    => self::$capability,
            'menu_slug'    => self::$menu_slug,//'ultraaddons-elementor-lite',
            'function'    => [ __CLASS__, 'welcome_page' ],
            //'function'    => [ __CLASS__, 'root_page' ], //When Welcome Page will Active, then it will active
            'icon_url'    => $icon_url,
            'position'    => 45,
        ];
        
        $menu = apply_filters( 'ultraaddons/admin/menu', $menu );
        
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
        
        if( ! did_action( 'ultraaddons_pro_init' ) ){
            /**
             * Get pro link added 
             * Here
             * 
             * @since 1.0.8.0
             */
            add_submenu_page( 'ultraaddons-elementor-lite', esc_html__( 'GET PRO', 'ultraaddons' ),  __( 'GET PRO', 'ultraaddons' ), self::$capability, 'https://codecanyon.net/item/ultraaddons-elementor-lite-pro/33337985?ref=CodeAstrology&utm_source=UltraAddons_Installed_Plugin',null,null );

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
                'page_title'    =>  __( 'UltraAddons Elementor Addons', 'ultraaddons' ),
                'menu_title'    =>  __( 'Welcome', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-elementor-lite',
                'function'      => [__CLASS__, 'welcome_page'],
                'position'      =>  1,
            ],
            
            [
                'parent_slug'   => self::$menu_slug,//$parent_slug,
                'page_title'    =>  __( 'UltraAddons Widgets', 'ultraaddons' ),
                'menu_title'    =>  __( 'Widgets', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-widgets', //When Welcome Page will Active, then it will active
                //'menu_slug'     => 'ultraaddons-elementor-lite',
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
            [
                'parent_slug'   => self::$menu_slug,//$parent_slug,
                'page_title'    =>  __( 'Custom Header Footer', 'ultraaddons' ),
                'menu_title'    =>  __( 'Header Footer', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-header-footer',
                'function'      => [__CLASS__, 'header_footer_page'],
                'position'      =>  2,
            ],
            
            [
                'parent_slug'   => self::$menu_slug,//$parent_slug,
                'page_title'    =>  __( 'Settings for UltraAddons', 'ultraaddons' ),
                'menu_title'    =>  __( 'Settings', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-elementor-settings',
                'function'      => [__CLASS__, 'settings_page'],
                'position'      =>  9992,
            ],
            
            
            [
                'parent_slug'   => self::$menu_slug,//$parent_slug,
                'page_title'    =>  __( 'Help & Others', 'ultraaddons' ),
                'menu_title'    =>  __( 'Help & Others', 'ultraaddons' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-help-n-others',
                'function'      => [__CLASS__, 'help_n_others_page'],
                'position'      =>  9991,
            ],
            
            
        ];
        
        self::$sub_menu = apply_filters( 'ultraaddons/admin/sub_menu', self::$sub_menu );
        
        if( empty( self::$sub_menu ) || ! is_array( self::$sub_menu ) ){
            self::$sub_menu = [];
        }
        
        return self::$sub_menu;
    }
    
    /**
     * Generated sub menu.
     * Use for Dashbard -> UltraAddons -> Menu Tab
     * Primarily we have removed welcome menu and help and others menu from tab.
     * 
     * @return Array Generated Array where unwanted submenu will not here
     * 
     * @by Saiful Islam
     * @date 9.9.2021
     */
    public static function get_submenu_for_header(){
        $header_submenu = self::get_submenu();
        $removed_menu = array(
            'ultraaddons-help-n-others',
            'ultraaddons-elementor-lite'
        );
        $removed_menu = apply_filters( 'ultraaddons/admin/sub_menu/remove', $removed_menu, __CLASS__ );
        if( ! is_array( $removed_menu ) ) return $header_submenu;
        
        //$removed_menu already checked. array or not
        foreach( $removed_menu as $r_menu ){
            $searched_key = array_search( $r_menu, $header_submenu );
            unset( $header_submenu[$searched_key] );
        }
        return $header_submenu;
    }

        /**
     * Opening Welcome Page for User.
     */
    public static function welcome_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/welcome_page.php';
        
        include_once self::$footer_file;
    }
    
    
    /**
     * Opening Widget User.
     */
    public static function widgets_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/widgets.php';
        
        include_once self::$footer_file;
    }
    
    
    /**
     * Opening Extension for User.
     */
    public static function extensions_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/extensions.php';
        
        include_once self::$footer_file;
    }
    
    /**
     * Opening Header Footer for User.
     */
    public static function header_footer_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/header-footer.php';
        
        include_once self::$footer_file;
    }
    
    /**
     * Opening Header Footer for User.
     */
    public static function settings_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/settings.php';
        
        include_once self::$footer_file;
    }
    
    public static function help_n_others_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/help-others.php';
        
        include_once self::$footer_file;
    }
    
    
}
Admin_Handle::init();