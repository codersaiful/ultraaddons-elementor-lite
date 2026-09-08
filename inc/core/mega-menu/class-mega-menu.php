<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();

/**
 * UltraAddons Mega Menu Core Controller
 *
 * Manages the custom post type for Elementor mega menu templates,
 * admin settings popup integration in Appearance > Menus,
 * and frontend asset rendering.
 *
 * @package UltraAddons
 * @since 3.1.0
 */
class Mega_Menu {

    /**
     * Post type identifier for UltraAddons Mega Menu templates
     */
    const POST_TYPE = 'ua_mega_menu';

    /**
     * Meta key used to store the template post ID on a nav menu item
     */
    const META_TEMPLATE_ID = '_ua_mega_menu_template_id';

    /**
     * Meta key used to store structured mega menu settings on a nav menu item
     */
    const META_SETTINGS = '_ua_mega_menu_settings';

    /**
     * Initialize core hooks and actions
     */
    public static function init() {
        // Register custom post type
        add_action( 'init', [ __CLASS__, 'register_post_type' ], 20 );

        // Add Elementor support for our custom post type
        add_filter( 'option_elementor_cpt_support', [ __CLASS__, 'enable_elementor_support' ] );
        add_filter( 'default_option_elementor_cpt_support', [ __CLASS__, 'enable_elementor_support' ] );

        // Blank canvas template for Elementor editor preview
        add_filter( 'template_include', [ __CLASS__, 'canvas_template' ], 999 );

        // Ajax actions for admin modal
        add_action( 'wp_ajax_ua_create_mega_template', [ __CLASS__, 'ajax_create_template' ] );
        add_action( 'wp_ajax_ua_save_mega_settings', [ __CLASS__, 'ajax_save_settings' ] );

        // Ajax action for mobile on-demand mega menu content
        add_action( 'wp_ajax_ua_get_mega_content', [ __CLASS__, 'ajax_get_mega_content' ] );
        add_action( 'wp_ajax_nopriv_ua_get_mega_content', [ __CLASS__, 'ajax_get_mega_content' ] );

        // Initialize admin UI on Appearance > Menus
        if ( is_admin() ) {
            require_once ULTRA_ADDONS_DIR . 'admin/mega-menu/class-mega-menu-admin.php';
            \UltraAddons\Admin\Mega_Menu_Admin::init();
        }
    }

    /**
     * Register Custom Post Type for Mega Menu templates
     */
    public static function register_post_type() {
        $labels = [
            'name'               => esc_html__( 'Ultra Mega Menus', 'ultraaddons-elementor-lite' ),
            'singular_name'      => esc_html__( 'Ultra Mega Menu', 'ultraaddons-elementor-lite' ),
            'add_new'            => esc_html__( 'Add New Mega Menu', 'ultraaddons-elementor-lite' ),
            'add_new_item'       => esc_html__( 'Add New Mega Menu Template', 'ultraaddons-elementor-lite' ),
            'edit_item'          => esc_html__( 'Edit Mega Menu Template', 'ultraaddons-elementor-lite' ),
            'all_items'          => esc_html__( 'All Mega Menus', 'ultraaddons-elementor-lite' ),
            'search_items'       => esc_html__( 'Search Mega Menus', 'ultraaddons-elementor-lite' ),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => false, // Accessible through Menus screen & editor
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'supports'            => [ 'title', 'editor', 'elementor' ],
            'hierarchical'        => false,
        ];

        register_post_type( self::POST_TYPE, $args );
    }

    /**
     * Add ua_mega_menu to Elementor's supported post types
     *
     * @param array $value
     * @return array
     */
    public static function enable_elementor_support( $value ) {
        if ( empty( $value ) || ! is_array( $value ) ) {
            $value = [ 'page', 'post' ];
        }

        if ( ! in_array( self::POST_TYPE, $value, true ) ) {
            $value[] = self::POST_TYPE;
        }

        return $value;
    }

    /**
     * Serve a clean blank canvas when editing or previewing a mega menu template
     *
     * @param string $template
     * @return string
     */
    public static function canvas_template( $template ) {
        if ( is_singular( self::POST_TYPE ) ) {
            $canvas = ULTRA_ADDONS_DIR . 'template/canvas.php';
            if ( file_exists( $canvas ) ) {
                return $canvas;
            }

            // Fallback to Elementor canvas if available
            if ( defined( 'ELEMENTOR_PATH' ) ) {
                $elementor_canvas = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
                if ( file_exists( $elementor_canvas ) ) {
                    return $elementor_canvas;
                }
            }
        }

        return $template;
    }

    /**
     * Helper: retrieve mega menu settings for a specific menu item
     *
     * @param int $menu_item_id
     * @return array
     */
    public static function get_settings( $menu_item_id ) {
        $default_settings = [
            'enable'          => '0',
            'width_type'      => 'container', // 'container', 'full', 'custom', 'fit'
            'custom_width'    => 760,          // UltraAddons distinct default
            'position'        => 'center',     // 'center', 'left', 'right'
            'mobile_mode'     => 'mega',       // 'mega', 'wp_sub', 'ajax'
            'icon'            => '',
            'icon_color'      => '#4f46e5',
            'icon_size'       => 16,           // UltraAddons distinct default
            'badge_text'      => '',
            'badge_position'  => 'floating',   // 'floating', 'inline'
            'badge_style'     => 'pill',       // 'pill', 'outline', 'soft'
            'badge_bg'        => '#4f46e5',    // UltraAddons Indigo
            'badge_color'     => '#ffffff',
            'badge_animation' => 'none',       // 'none', 'pulse-dot', 'shimmer'
        ];

        $saved = get_post_meta( $menu_item_id, self::META_SETTINGS, true );
        if ( is_array( $saved ) ) {
            return wp_parse_args( $saved, $default_settings );
        }

        return $default_settings;
    }

    /**
     * Helper: check if a menu item has an active mega menu
     *
     * @param int $menu_item_id
     * @return bool
     */
    public static function has_mega_menu( $menu_item_id ) {
        $settings = self::get_settings( $menu_item_id );
        if ( empty( $settings['enable'] ) || '1' !== (string) $settings['enable'] ) {
            return false;
        }

        $template_id = get_post_meta( $menu_item_id, self::META_TEMPLATE_ID, true );
        return ! empty( $template_id );
    }

    /**
     * Helper: retrieve template ID for a menu item
     *
     * @param int $menu_item_id
     * @return int
     */
    public static function get_template_id( $menu_item_id ) {
        return (int) get_post_meta( $menu_item_id, self::META_TEMPLATE_ID, true );
    }

    /**
     * Ajax: Create or retrieve Mega Menu Template for a given menu item
     */
    public static function ajax_create_template() {
        check_ajax_referer( 'ua_mega_menu_admin_nonce', 'security' );

        if ( ! current_user_can( 'edit_theme_options' ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Permission denied.', 'ultraaddons-elementor-lite' ) ] );
        }

        $item_id = isset( $_POST['item_id'] ) ? absint( $_POST['item_id'] ) : 0;
        if ( ! $item_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid menu item ID.', 'ultraaddons-elementor-lite' ) ] );
        }

        $menu_item = get_post( $item_id );
        if ( ! $menu_item || 'nav_menu_item' !== $menu_item->post_type ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Menu item not found.', 'ultraaddons-elementor-lite' ) ] );
        }

        $template_id = get_post_meta( $item_id, self::META_TEMPLATE_ID, true );

        if ( ! $template_id || ! get_post( $template_id ) ) {
            $item_title = ! empty( $menu_item->post_title ) ? $menu_item->post_title : ( ! empty( $menu_item->title ) ? $menu_item->title : 'Item-' . $item_id );
            
            $template_id = wp_insert_post( [
                'post_title'   => sprintf( esc_html__( 'Ultra Mega Menu: %s (Item #%d)', 'ultraaddons-elementor-lite' ), sanitize_text_field( $item_title ), $item_id ),
                'post_status'  => 'publish',
                'post_type'    => self::POST_TYPE,
            ] );

            if ( is_wp_error( $template_id ) || ! $template_id ) {
                wp_send_json_error( [ 'message' => esc_html__( 'Failed to create template post.', 'ultraaddons-elementor-lite' ) ] );
            }

            // Set Elementor canvas template type
            update_post_meta( $template_id, '_elementor_edit_mode', 'builder' );
            update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' );
            update_post_meta( $item_id, self::META_TEMPLATE_ID, $template_id );
        }

        // Generate Elementor editor URL
        $editor_url = add_query_arg(
            [
                'post'   => $template_id,
                'action' => 'elementor',
            ],
            admin_url( 'post.php' )
        );

        wp_send_json_success( [
            'template_id' => $template_id,
            'editor_url'  => $editor_url,
        ] );
    }

    /**
     * Ajax: Save Mega Menu settings for a given menu item
     */
    public static function ajax_save_settings() {
        check_ajax_referer( 'ua_mega_menu_admin_nonce', 'security' );

        if ( ! current_user_can( 'edit_theme_options' ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Permission denied.', 'ultraaddons-elementor-lite' ) ] );
        }

        $item_id = isset( $_POST['item_id'] ) ? absint( $_POST['item_id'] ) : 0;
        if ( ! $item_id ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid menu item ID.', 'ultraaddons-elementor-lite' ) ] );
        }

        $raw_settings = isset( $_POST['settings'] ) && is_array( $_POST['settings'] ) ? $_POST['settings'] : [];

        $sanitized = [
            'enable'          => ! empty( $raw_settings['enable'] ) && '1' === (string) $raw_settings['enable'] ? '1' : '0',
            'width_type'      => in_array( $raw_settings['width_type'] ?? '', [ 'container', 'full', 'custom', 'fit' ], true ) ? sanitize_key( $raw_settings['width_type'] ) : 'container',
            'custom_width'    => isset( $raw_settings['custom_width'] ) ? max( 280, min( 1920, absint( $raw_settings['custom_width'] ) ) ) : 760,
            'position'        => in_array( $raw_settings['position'] ?? '', [ 'center', 'left', 'right' ], true ) ? sanitize_key( $raw_settings['position'] ) : 'center',
            'mobile_mode'     => in_array( $raw_settings['mobile_mode'] ?? '', [ 'mega', 'wp_sub', 'ajax' ], true ) ? sanitize_key( $raw_settings['mobile_mode'] ) : 'mega',
            'icon'            => sanitize_text_field( $raw_settings['icon'] ?? '' ),
            'icon_color'      => sanitize_hex_color( $raw_settings['icon_color'] ?? '' ) ?: '#4f46e5',
            'icon_size'       => isset( $raw_settings['icon_size'] ) ? max( 10, min( 48, absint( $raw_settings['icon_size'] ) ) ) : 16,
            'badge_text'      => sanitize_text_field( $raw_settings['badge_text'] ?? '' ),
            'badge_position'  => in_array( $raw_settings['badge_position'] ?? '', [ 'floating', 'inline' ], true ) ? sanitize_key( $raw_settings['badge_position'] ) : 'floating',
            'badge_style'     => in_array( $raw_settings['badge_style'] ?? '', [ 'pill', 'outline', 'soft' ], true ) ? sanitize_key( $raw_settings['badge_style'] ) : 'pill',
            'badge_bg'        => sanitize_hex_color( $raw_settings['badge_bg'] ?? '' ) ?: '#4f46e5',
            'badge_color'     => sanitize_hex_color( $raw_settings['badge_color'] ?? '' ) ?: '#ffffff',
            'badge_animation' => in_array( $raw_settings['badge_animation'] ?? '', [ 'none', 'pulse-dot', 'shimmer' ], true ) ? sanitize_key( $raw_settings['badge_animation'] ) : 'none',
        ];

        update_post_meta( $item_id, self::META_SETTINGS, $sanitized );

        wp_send_json_success( [
            'message'  => esc_html__( 'Settings saved successfully!', 'ultraaddons-elementor-lite' ),
            'settings' => $sanitized,
        ] );
    }

    /**
     * Ajax: Fetch rendered Mega Menu content for on-demand mobile AJAX loading
     */
    public static function ajax_get_mega_content() {
        $template_id = isset( $_GET['template_id'] ) ? absint( $_GET['template_id'] ) : 0;
        if ( ! $template_id || get_post_type( $template_id ) !== self::POST_TYPE ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid template ID.', 'ultraaddons-elementor-lite' ) ] );
        }

        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Elementor is required.', 'ultraaddons-elementor-lite' ) ] );
        }

        $html = self::render_template( $template_id );

        wp_send_json_success( [
            'html' => $html,
        ] );
    }

    /**
     * Helper: render mega menu content on the frontend
     *
     * Ensures all Elementor post styles, atomic flexbox rules, and widget dependencies
     * are properly loaded on ANY page (including WooCommerce archives, blog pages, 404, etc.).
     *
     * @param int $template_id
     * @return string HTML
     */
    public static function render_template( $template_id ) {
        if ( ! $template_id || ! class_exists( '\Elementor\Plugin' ) ) {
            return '';
        }

        $elementor = \Elementor\Plugin::instance();

        // 1. Enable widget-level asset dependencies (icon fonts, widget CSS files)
        if ( class_exists( '\Elementor\Core\Base\Elements_Iteration_Actions\Assets' ) ) {
            $page_assets = get_post_meta( $template_id, \Elementor\Core\Base\Elements_Iteration_Actions\Assets::ASSETS_META_KEY, true );
            if ( ! empty( $page_assets ) && isset( $elementor->assets_loader ) ) {
                $elementor->assets_loader->enable_assets( $page_assets );
            }
        }

        // 2. Render builder content with inline CSS flag
        $content = $elementor->frontend->get_builder_content_for_display( $template_id, true );

        // 3. Guarantee all necessary template and atomic CSS are embedded
        $extra_css = '';

        // Template Post CSS
        if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
            $css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
            $css_file->enqueue();

            // If Elementor did not print inline style block for this post, retrieve and inject it
            if ( strpos( $content, 'elementor-post-' . $template_id ) === false ) {
                $post_css = $css_file->get_content();
                if ( ! empty( $post_css ) ) {
                    $extra_css .= $post_css . "\n";
                }
            }
        }

        // Elementor 3.16+ Atomic and Local Container Styles
        $upload_dir        = wp_upload_dir();
        $elementor_css_dir = $upload_dir['basedir'] . '/elementor/css';

        if ( is_dir( $elementor_css_dir ) ) {
            // Include Elementor's base atomic CSS if not already on the page (e.g. WooCommerce archives)
            static $base_atomic_loaded = false;
            if ( ! $base_atomic_loaded && ! wp_style_is( 'elementor-base-desktop', 'enqueued' ) ) {
                $base_file = $elementor_css_dir . '/base-desktop.css';
                if ( file_exists( $base_file ) ) {
                    $extra_css .= file_get_contents( $base_file ) . "\n";
                    $base_atomic_loaded = true;
                }
            }

            // Include local template CSS files (desktop and responsive variants)
            $local_files = glob( $elementor_css_dir . '/local-' . (int) $template_id . '-*.css' );
            if ( ! empty( $local_files ) ) {
                foreach ( $local_files as $lf ) {
                    if ( file_exists( $lf ) ) {
                        $extra_css .= file_get_contents( $lf ) . "\n";
                    }
                }
            }
        }

        if ( ! empty( $extra_css ) ) {
            $content = '<style class="ua-mega-inline-styles" data-mega-template="' . (int) $template_id . '">' . $extra_css . '</style>' . $content;
        }

        return $content;
    }
}
