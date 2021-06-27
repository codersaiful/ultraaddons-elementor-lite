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
        $ultraaddons_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ultraaddons' ) . '" target="_blank">'.esc_html__( 'Support','ultraaddons' ).'</a>';
        $ultraaddons_links[] = '<a href="https://github.com/codersaiful/ultraaddons-elementor-lite" title="' . esc_attr__( 'Github Repo Link', 'ultraaddons' ) . '" target="_blank">'.esc_html__( 'Github Repository','ultraaddons' ).'</a>';
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
        
        $menu = [
            'page_title'    => __( 'UltraAddons Elementor Addons', 'ultraaddons' ),
            'menu_title'    => __( 'UltraAddons', 'ultraaddons' ),
            'capability'    => self::$capability,
            'menu_slug'    => self::$menu_slug,//'ultraaddons-elementor-lite',
            'function'    => [ __CLASS__, 'widgets_page' ],
            //'function'    => [ __CLASS__, 'root_page' ], //When Welcome Page will Active, then it will active
            'icon_url'    => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMy4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyMCAyMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAgMjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQogICAgCS51bHRyYWFkZG9ucy1zdmctYmxhY2stMXtmaWxsOiNmZmZmZmY7fQ0KCS51bHRyYWFkZG9ucy1zdmctYmxhY2stMntmaWxsOiMxN2Q0ZmY7fQ0KPC9zdHlsZT4NCjxnPg0KCTxwYXRoIGNsYXNzPSJ1bHRyYWFkZG9ucy1zdmctYmxhY2stMSIgZD0iTTAuOTYsNi4zN0MwLjk2LDYuMzcsMC45Niw2LjM3LDAuOTYsNi4zN0MwLjk2LDYuMzcsMC45Niw2LjM3LDAuOTYsNi4zN0MwLjk2LDYuMzcsMC45Niw2LjM3LDAuOTYsNi4zNw0KCQljMC4wMS0wLjAxLDAuMDItMC4wMywwLjAzLTAuMDRjMC45Ni0xLjE4LDIuMzktMS45NywzLjk5LTIuMUM1LjE0LDQuMjEsNS4zLDQuMiw1LjQ2LDQuMmMwLjM0LDAsMC42NywwLjAzLDEsMC4wOQ0KCQlDOC4zLDQuNjEsOS44NCw1LjgsMTAuNjQsNy40MmMwLjY3LDEuMzYsMi41MSwxLjU0LDMuNCwwLjM5YzAsMCwwLDAsMCwwYzAuMDYtMC4wOCwwLjExLTAuMTYsMC4xNi0wLjI1YzAsMCwwLDAsMCwwDQoJCWMwLjMxLTAuNSwwLjUyLTEuMDgsMC41OS0xLjY5YzAuMDQtMC4yNSwwLjA2LTAuNTEsMC4wNi0wLjc4YzAtMi43My0yLjItNC45NS00LjkzLTQuOThjLTAuMDksMC0wLjE5LDAtMC4yOCwwLjAxDQoJCWMtMC4wMSwwLTAuMDEsMC0wLjAyLDBDNi42NSwwLjIzLDQsMS42NSwyLjI1LDMuODJjLTAuNzUsMC45My0xLjM0LDItMS43MiwzLjE2QzAuNjYsNi43NiwwLjgsNi41NiwwLjk2LDYuMzcNCgkJQzAuOTYsNi4zNywwLjk2LDYuMzcsMC45Niw2LjM3eiIvPg0KCTxwYXRoIGNsYXNzPSJ1bHRyYWFkZG9ucy1zdmctYmxhY2stMiIgZD0iTTYuMzEsMTkuMTFDNi4zMSwxOS4xMSw2LjMxLDE5LjExLDYuMzEsMTkuMTFDNi4zMSwxOS4xMSw2LjMxLDE5LjExLDYuMzEsMTkuMTENCgkJQzYuMzEsMTkuMTEsNi4zMSwxOS4xMSw2LjMxLDE5LjExYy0wLjAxLTAuMDEtMC4wMy0wLjAyLTAuMDQtMC4wM2MtMS4xOC0wLjk2LTEuOTctMi4zOS0yLjEtMy45OWMtMC4wMS0wLjE2LTAuMDItMC4zMi0wLjAyLTAuNDgNCgkJYzAtMC4zNCwwLjAzLTAuNjcsMC4wOS0xYzAuMzItMS44NCwxLjUxLTMuMzgsMy4xMy00LjE4YzEuMzYtMC42NywxLjU0LTIuNTEsMC4zOS0zLjRjMCwwLDAsMCwwLDBDNy42Niw1Ljk2LDcuNTgsNS45MSw3LjUsNS44Ng0KCQljMCwwLDAsMCwwLDBjLTAuNS0wLjMxLTEuMDgtMC41Mi0xLjY5LTAuNTlDNS41NSw1LjIyLDUuMjksNS4yLDUuMDMsNS4yYy0yLjczLDAtNC45NSwyLjItNC45OCw0LjkzYzAsMC4wOSwwLDAuMTksMC4wMSwwLjI4DQoJCWMwLDAuMDEsMCwwLjAxLDAsMC4wMmMwLjExLDIuOTgsMS41Miw1LjYzLDMuNjksNy4zOGMwLjkzLDAuNzUsMiwxLjM0LDMuMTYsMS43MkM2LjcsMTkuNDEsNi41LDE5LjI2LDYuMzEsMTkuMTENCgkJQzYuMzEsMTkuMTEsNi4zMSwxOS4xMSw2LjMxLDE5LjExeiIvPg0KCTxwYXRoIGNsYXNzPSJ1bHRyYWFkZG9ucy1zdmctYmxhY2stMSIgZD0iTTE5LjA3LDEzLjc3QzE5LjA3LDEzLjc3LDE5LjA3LDEzLjc3LDE5LjA3LDEzLjc3QzE5LjA3LDEzLjc2LDE5LjA3LDEzLjc2LDE5LjA3LDEzLjc3DQoJCUMxOS4wNywxMy43NiwxOS4wNywxMy43NiwxOS4wNywxMy43N2MtMC4wMSwwLjAxLTAuMDIsMC4wMy0wLjAzLDAuMDRjLTAuOTYsMS4xOC0yLjM5LDEuOTctMy45OSwyLjENCgkJYy0wLjE2LDAuMDEtMC4zMiwwLjAyLTAuNDgsMC4wMmMtMC4zNCwwLTAuNjctMC4wMy0xLTAuMDljLTEuODQtMC4zMi0zLjM4LTEuNTEtNC4xOC0zLjEzYy0wLjY3LTEuMzYtMi41MS0xLjU0LTMuNC0wLjM5DQoJCWMwLDAsMCwwLDAsMGMtMC4wNiwwLjA4LTAuMTEsMC4xNi0wLjE2LDAuMjVjMCwwLDAsMCwwLDBjLTAuMzEsMC41LTAuNTIsMS4wOC0wLjU5LDEuNjljLTAuMDQsMC4yNS0wLjA2LDAuNTEtMC4wNiwwLjc4DQoJCWMwLDIuNzMsMi4yLDQuOTUsNC45Myw0Ljk4YzAuMDksMCwwLjE5LDAsMC4yOC0wLjAxYzAuMDEsMCwwLjAxLDAsMC4wMiwwYzIuOTgtMC4xMSw1LjYzLTEuNTIsNy4zOC0zLjY5DQoJCWMwLjc1LTAuOTMsMS4zNC0yLDEuNzItMy4xNkMxOS4zNywxMy4zNywxOS4yMywxMy41NywxOS4wNywxMy43N0MxOS4wNywxMy43NywxOS4wNywxMy43NywxOS4wNywxMy43N3oiLz4NCgk8cGF0aCBjbGFzcz0idWx0cmFhZGRvbnMtc3ZnLWJsYWNrLTIiIGQ9Ik0xMy42OSwxLjAzQzEzLjY5LDEuMDMsMTMuNjksMS4wMywxMy42OSwxLjAzQzEzLjY5LDEuMDMsMTMuNjksMS4wMywxMy42OSwxLjAzDQoJCUMxMy42OSwxLjAzLDEzLjY5LDEuMDMsMTMuNjksMS4wM2MwLjAxLDAuMDEsMC4wMywwLjAyLDAuMDQsMC4wM2MxLjE4LDAuOTYsMS45NywyLjM5LDIuMSwzLjk5YzAuMDEsMC4xNiwwLjAyLDAuMzIsMC4wMiwwLjQ4DQoJCWMwLDAuMzQtMC4wMywwLjY3LTAuMDksMWMtMC4zMiwxLjg0LTEuNTEsMy4zOC0zLjEzLDQuMThjLTEuMzYsMC42Ny0xLjU0LDIuNTEtMC4zOSwzLjRjMCwwLDAsMCwwLDANCgkJYzAuMDgsMC4wNiwwLjE2LDAuMTEsMC4yNSwwLjE2YzAsMCwwLDAsMCwwYzAuNSwwLjMxLDEuMDgsMC41MiwxLjY5LDAuNTljMC4yNSwwLjA0LDAuNTEsMC4wNiwwLjc4LDAuMDYNCgkJYzIuNzMsMCw0Ljk1LTIuMiw0Ljk4LTQuOTNjMC0wLjA5LDAtMC4xOS0wLjAxLTAuMjhjMC0wLjAxLDAtMC4wMSwwLTAuMDJjLTAuMTEtMi45OC0xLjUyLTUuNjMtMy42OS03LjM4DQoJCWMtMC45My0wLjc1LTItMS4zNC0zLjE2LTEuNzJDMTMuMywwLjczLDEzLjUsMC44NywxMy42OSwxLjAzQzEzLjY5LDEuMDMsMTMuNjksMS4wMywxMy42OSwxLjAzeiIvPg0KPC9nPg0KPC9zdmc+DQo=',
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
//                'menu_slug'     => 'ultraaddons-widgets', //When Welcome Page will Active, then it will active
                'menu_slug'     => 'ultraaddons-elementor-lite',
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
     * Opening Welcome Page for User.
     */
    public static function root_page() {
        include_once self::$header_file;
        
        include ULTRA_ADDONS_DIR . 'admin/pages/main.php';
        
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