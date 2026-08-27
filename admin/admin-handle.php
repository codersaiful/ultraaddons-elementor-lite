<?php
namespace UltraAddons\Admin;

use UltraAddons\WP\Header_Footer_Post as HF_Post;

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
    
    public static $ultraaddons_sub_menu = array();
    public static $capability = ULTRA_ADDONS_CAPABILITY;
    public static $ultraaddons_menu_slug = 'ultraaddons-elementor-lite';
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
        

        /**
         * Actually handle Custom Fonts Taxonomy
         * when activate as submenu
         * 
         * We can handle all other sub menu when it will select as sub menu
         * 
         * @since 1.1.0.2
         */
        add_filter( 'parent_file', [ __CLASS__, 'keep_menu_open' ], 100 );
        add_filter( 'submenu_file', [ __CLASS__, 'keep_submenu_open' ], 100, 2 );


    }
    
    /**
     * Enqueueing File for Admin Section
     * 
     * @since 1.0.0.5
     */
    public static function get_enqueue( $hook_suffix ) {
        $page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
        $screen = get_current_screen();
        $ultraaddons_pages = [
            self::$ultraaddons_menu_slug,
            'ultraaddons-widgets',
            'ultraaddons-extensions',
            'ultraaddons-elementor-settings',
        ];
        $is_header_footer = $screen && HF_Post::$post_type === ( $screen->post_type ?? '' );
        $is_custom_fonts = $screen && 'ultraaddons-custom-fonts' === ( $screen->taxonomy ?? '' );

        if ( ! in_array( $page, $ultraaddons_pages, true ) && ! $is_header_footer && ! $is_custom_fonts ) {
            return;
        }

        wp_enqueue_style( 'ultraaddons-admin-style', ULTRA_ADDONS_ASSETS . 'css/admin.css', [], ULTRA_ADDONS_VERSION );
        wp_enqueue_script( 'ultraaddons-admin-script', ULTRA_ADDONS_ASSETS . 'js/admin.js', [ 'jquery' ], ULTRA_ADDONS_VERSION, true );
        wp_enqueue_style( 'ultraaddons-icon-font', ULTRA_ADDONS_ASSETS . 'icons/ultraaddons/css/ultraaddons.css', [], ULTRA_ADDONS_VERSION );
        wp_enqueue_style( 'ultraaddons-extra-icons-style', ULTRA_ADDONS_ASSETS . 'icons/ultra-addons-extra/css/fontello.css', [], ULTRA_ADDONS_VERSION );
        if ( wp_style_is( 'elementor-icons', 'registered' ) ) {
            wp_enqueue_style( 'elementor-icons' );
        } elseif ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_enqueue_style( 'elementor-icons', ELEMENTOR_ASSETS_URL . 'lib/eicons/css/elementor-icons.min.css', [], defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : ULTRA_ADDONS_VERSION );
        }

        if ( self::$ultraaddons_menu_slug === $page ) {
            wp_enqueue_style( 'owl-corousel-style', ULTRA_ADDONS_ASSETS . 'vendor/css/owl/owl.carousel.min.css', [], ULTRA_ADDONS_VERSION );
            wp_enqueue_script( 'owl-corousel-script', ULTRA_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js', [ 'jquery' ], ULTRA_ADDONS_VERSION, true );
        }

        if ( in_array( $page, [ 'ultraaddons-widgets', 'ultraaddons-extensions' ], true ) ) {
            wp_enqueue_style( 'remodal-style', ULTRA_ADDONS_ASSETS . 'vendor/remodal/css/remodal.css', [], ULTRA_ADDONS_VERSION );
            wp_enqueue_script( 'remodal-script', ULTRA_ADDONS_ASSETS . 'vendor/remodal/js/remodal.min.js', [ 'jquery' ], ULTRA_ADDONS_VERSION, true );
        }

        if ( $is_header_footer ) {
            wp_enqueue_style( 'select2', ULTRA_ADDONS_ASSETS . 'vendor/select2/css/select2.min.css', [], ULTRA_ADDONS_VERSION );
            wp_enqueue_script( 'select2', ULTRA_ADDONS_ASSETS . 'vendor/select2/js/select2.full.min.js', [ 'jquery' ], ULTRA_ADDONS_VERSION, true );
        }
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
        $ultraaddons_links[] = '<a href="' . admin_url( 'admin.php?page=ultraaddons-elementor-lite' ) . '" title="' . esc_attr__( 'Welcome to UltraAddons', 'ultraaddons-elementor-lite' ) . '" target="_blank">' . esc_html__( 'Welcome','ultraaddons-elementor-lite' ).'</a>';
        $ultraaddons_links[] = '<a href="https://ultraaddons.com/pricing/" title="' . esc_attr__( 'GET PRO', 'ultraaddons-elementor-lite' ) . '" target="_blank">' . esc_html__( 'GET PRO','ultraaddons-elementor-lite' ).'</a>';
        $ultraaddons_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ultraaddons-elementor-lite' ) . '" target="_blank">'.esc_html__( 'Support','ultraaddons-elementor-lite' ).'</a>';
        //$ultraaddons_links[] = '<a href="https://github.com/codersaiful/ultraaddons-elementor-lite" title="' . esc_attr__( 'Github Repo Link', 'ultraaddons-elementor-lite' ) . '" target="_blank">'.esc_html__( 'Github Repository','ultraaddons-elementor-lite' ).'</a>';
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
            'page_title'    => __( 'UltraAddons Elementor Addons', 'ultraaddons-elementor-lite' ),
            'menu_title'    => __( 'Ultra Addons', 'ultraaddons-elementor-lite' ),
            'capability'    => self::$capability,
            'menu_slug'    => self::$ultraaddons_menu_slug,//'ultraaddons-elementor-lite',
            'function'    => [ __CLASS__, 'welcome_page' ],
            //'function'    => [ __CLASS__, 'root_page' ], //When Welcome Page will Active, then it will active
            'icon_url'    => $icon_url,
            'position'    => 45,
        ];
        
        $menu = apply_filters( 'ultraaddons/admin/menu', $menu );
        
        $page_title = isset( $menu['page_title'] ) ? $menu['page_title'] : false;
        $ultraaddons_menu_title = isset( $menu['menu_title'] ) ? $menu['menu_title'] : false;
        $capability = isset( $menu['capability'] ) ? $menu['capability'] : false; //'manage_woocommerce';//
        $ultraaddons_menu_slug = isset( $menu['menu_slug'] ) ? $menu['menu_slug'] : false;
        $function = isset( $menu['function'] ) ? $menu['function'] : false;
        $icon_url = isset( $menu['icon_url'] ) ? $menu['icon_url'] : false;
        $position = isset( $menu['position'] ) ? $menu['position'] : false;
        
        
        add_menu_page( $page_title, $ultraaddons_menu_title, $capability, $ultraaddons_menu_slug, $function, $icon_url, $position );
        
        /**
         * Adding Submenu
         */
        self::add_submenu( $ultraaddons_menu_slug );
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
            $ultraaddons_menu_title = isset( $menu['menu_title'] ) ? $menu['menu_title'] : false;
            $capability = isset( $menu['capability'] ) ? $menu['capability'] : false;
            $ultraaddons_menu_slug = isset( $menu['menu_slug'] ) ? $menu['menu_slug'] : false;
            $function = isset( $menu['function'] ) ? $menu['function'] : false;
            $icon_url = isset( $menu['icon_url'] ) ? $menu['icon_url'] : false;
            $position = isset( $menu['position'] ) ? $menu['position'] : 100; //Actually $position param should be Number, can't be bool(false/true)

            add_submenu_page($parent_slug, $page_title, $ultraaddons_menu_title, $capability, $ultraaddons_menu_slug, $function, $position);
        }
        
        if( ! did_action( 'ultraaddons_pro_init' ) ){
            /**
             * Get pro link added 
             * Here
             * 
             * @since 1.0.8.0
             */
            add_submenu_page( 'ultraaddons-elementor-lite', esc_html__( 'GET PRO', 'ultraaddons-elementor-lite' ),  __( 'GET PRO', 'ultraaddons-elementor-lite' ), self::$capability, 'https://ultraaddons.com/pricing/',null,null );

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
        self::$ultraaddons_sub_menu = [
            [
                'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
                'page_title'    =>  __( 'UltraAddons Elementor Addons', 'ultraaddons-elementor-lite' ),
                'menu_title'    =>  __( 'Welcome', 'ultraaddons-elementor-lite' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-elementor-lite',
                'function'      => [__CLASS__, 'welcome_page'],
                'position'      =>  1,
            ],
            
            [
                'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
                'page_title'    =>  __( 'UltraAddons Widgets', 'ultraaddons-elementor-lite' ),
                'menu_title'    =>  __( 'Widgets', 'ultraaddons-elementor-lite' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-widgets', //When Welcome Page will Active, then it will active
                //'menu_slug'     => 'ultraaddons-elementor-lite',
                'function'      => [__CLASS__, 'widgets_page'],
                'position'      =>  1,
            ],
            
            [
                'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
                'page_title'    =>  __( 'UltraAddons Extensions', 'ultraaddons-elementor-lite' ),
                'menu_title'    =>  __( 'Extensions', 'ultraaddons-elementor-lite' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-extensions',
                'function'      => [__CLASS__, 'extensions_page'],
                'position'      =>  2,
            ],
            // [
            //     'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
            //     'page_title'    =>  __( 'Custom Header Footer', 'ultraaddons-elementor-lite' ),
            //     'menu_title'    =>  __( 'Header Footer', 'ultraaddons-elementor-lite' ),
            //     'capability'    => self::$capability,
            //     'menu_slug'     => 'ultraaddons-header-footer',
            //     'function'      => [__CLASS__, 'header_footer_page'],
            //     'position'      =>  3,
            // ],
            
            [
                'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
                'page_title'    =>  __( 'Custom Header Footer Template', 'ultraaddons-elementor-lite' ),
                'menu_title'    =>  __( 'Header Footer', 'ultraaddons-elementor-lite' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'edit.php?post_type=' . HF_Post::$post_type,
                // 'position'      =>  2,
            ],
            
            [
                'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
                'page_title'    =>  __( 'Custom Fonts', 'ultraaddons-elementor-lite' ),
                'menu_title'    =>  __( 'Custom Fonts', 'ultraaddons-elementor-lite' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'edit-tags.php?taxonomy=ultraaddons-custom-fonts',
                // 'position'      =>  2,
            ],
            
            [
                'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
                'page_title'    =>  __( 'Settings for UltraAddons', 'ultraaddons-elementor-lite' ),
                'menu_title'    =>  __( 'Settings', 'ultraaddons-elementor-lite' ),
                'capability'    => self::$capability,
                'menu_slug'     => 'ultraaddons-elementor-settings',
                'function'      => [__CLASS__, 'settings_page'],
                'position'      =>  9992,
            ],
            
            //has removed @since 1.1.4.0
            // [
            //     'parent_slug'   => self::$ultraaddons_menu_slug,//$parent_slug,
            //     'page_title'    =>  __( 'Help & Others', 'ultraaddons-elementor-lite' ),
            //     'menu_title'    =>  __( 'Help & Others', 'ultraaddons-elementor-lite' ),
            //     'capability'    => self::$capability,
            //     'menu_slug'     => 'ultraaddons-help-n-others',
            //     'function'      => [__CLASS__, 'help_n_others_page'],
            //     'position'      =>  9991,
            // ],
            
            
        ];
        
        self::$ultraaddons_sub_menu = apply_filters( 'ultraaddons/admin/sub_menu', self::$ultraaddons_sub_menu );
        
        if( empty( self::$ultraaddons_sub_menu ) || ! is_array( self::$ultraaddons_sub_menu ) ){
            self::$ultraaddons_sub_menu = [];
        }
        
        return self::$ultraaddons_sub_menu;
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
        
        foreach ( $header_submenu as $key => $menu ) {
            if ( isset( $menu['menu_slug'] ) && in_array( $menu['menu_slug'], $removed_menu, true ) ) {
                unset( $header_submenu[ $key ] );
            }
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
    
    // /**
    //  * Opening Header Footer for User.
    //  */
    // public static function header_footer_page() {
    //     include_once self::$header_file;
        
    //     include ULTRA_ADDONS_DIR . 'admin/pages/header-footer.php';
        
    //     include_once self::$footer_file;
    // }
    
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
    
 
    /**
     * Keep select UltraAddons menu option,
     * when select any submenu of UltraAddons menu
     * 
     * Actually it was not working by default, when choosing header-footer submenu and for custom fonts submenu
     * we did it with condition for choosing and keep open main menu
     * 
     * @since 1.1.0.3
     */
    public static function keep_menu_open( $parent_file ){
        global $current_screen;
        //var_dump($current_screen);
        $hf_post_type =HF_Post::$post_type; //It's actually 'header_footer' \UltraAddons\WP\Header_Footer_Post::$post_type; //It's actually 'header_footer'
        if( $current_screen->post_type  == HF_Post::$post_type ) return self::$ultraaddons_menu_slug;//'ultraaddons-elementor-lite'; 
        if( $current_screen->taxonomy == 'ultraaddons-custom-fonts' ) return self::$ultraaddons_menu_slug;//'ultraaddons-elementor-lite';
        
        //Return to default
        return $parent_file;
    }

    /**
     * Keep select Header footer template submenu,
     * when adding new templatte
     * 
     * @since 1.1.0.4
     */
    public static function keep_submenu_open( $submenu_file, $parent_file ){

        if( $parent_file !== self::$ultraaddons_menu_slug ) return $submenu_file; //Return to default

        global $current_screen;

        if( $current_screen->post_type  == HF_Post::$post_type ) return 'edit.php?post_type=' . HF_Post::$post_type;

        //Return to default
        return $submenu_file;
    }
    
    
}
Admin_Handle::init();
