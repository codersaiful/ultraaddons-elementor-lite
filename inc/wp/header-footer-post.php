<?php
namespace UltraAddons\WP;

class Header_Footer_Post{
    
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_post' ],99 );
        add_action( 'admin_menu', [ __CLASS__, 'add_submenu' ] );
        add_filter( 'template_include', [ __CLASS__, 'template_include' ] );
    }
    
    /**
     * Mainly When we will create custom Header footer post by Elementor
     * or normal Post
     * 
     * and when we will view these Header Footer Template in single page
     * we need to visit without any sidebar.
     * Even without theme's header or without theme footer
     * 
     * That's why, we need new and custom template for single.php
     * only when post type is header_footer
     * 
     * @param String $template_file Current and default template path will return here.
     * @return String Template puth
     * 
     * @since 1.0.4
     */
    public static function template_include( $template_file ) {
        if( ! is_singular() ){
            return $template_file;
        }
        $type = get_post_type();
        if( $type == 'header_footer' ){
            $template = ULTRA_ADDONS_DIR . 'template/header-footer-elementor.php';
            return is_file( $template ) ? $template : $template_file;
        }

        return $template_file;
    }
    
    /**
     * Add submenu under UltraAddons Menu
     * Mainly for Creating Menu List
     * 
     * @since 1.0.4.0
     */
    public static function add_submenu() {
        $parent_slug = 'ultraaddons-elementor-lite';
        $page_title = $menu_title = esc_html__( 'Header & Footer Templates', 'medilac' );
        $capability = ULTRA_ADDONS_CAPABILITY;//'edit_themes';
        $menu_slug = admin_url( 'edit.php?post_type=header_footer' );
        add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, '', 3 );
    }
    
    public static function register_post() {
        /**
	 * Post Type: Header & Footer.
	 */

	$labels = [
		"name" => __( "Header & Footer", "ultraaddons" ),
		"singular_name" => __( "Header & Footer", "ultraaddons" ),
		"menu_name" => __( "Header & Footer", "ultraaddons" ),
		"all_items" => __( "All Header & Footer", "ultraaddons" ),
		"add_new" => __( "Add new", "ultraaddons" ),
		"add_new_item" => __( "Add new Header & Footer", "ultraaddons" ),
		"edit_item" => __( "Edit Header & Footer", "ultraaddons" ),
		"new_item" => __( "New Header & Footer", "ultraaddons" ),
		"view_item" => __( "View Header & Footer", "ultraaddons" ),
		"view_items" => __( "View Header & Footer", "ultraaddons" ),
		"search_items" => __( "Search Header & Footer", "ultraaddons" ),
		"not_found" => __( "No Header & Footer found", "ultraaddons" ),
		"not_found_in_trash" => __( "No Header & Footer found in trash", "ultraaddons" ),
		"parent" => __( "Parent Header & Footer:", "ultraaddons" ),
		"featured_image" => __( "Featured image for this Header & Footer", "ultraaddons" ),
		"set_featured_image" => __( "Set featured image for this Header & Footer", "ultraaddons" ),
		"remove_featured_image" => __( "Remove featured image for this Header & Footer", "ultraaddons" ),
		"use_featured_image" => __( "Use as featured image for this Header & Footer", "ultraaddons" ),
		"archives" => __( "Header & Footer archives", "ultraaddons" ),
		"insert_into_item" => __( "Insert into Header & Footer", "ultraaddons" ),
		"uploaded_to_this_item" => __( "Upload to this Header & Footer", "ultraaddons" ),
		"filter_items_list" => __( "Filter Header & Footer list", "ultraaddons" ),
		"items_list_navigation" => __( "Header & Footer list navigation", "ultraaddons" ),
		"items_list" => __( "Header & Footer list", "ultraaddons" ),
		"attributes" => __( "Header & Footer attributes", "ultraaddons" ),
		"name_admin_bar" => __( "Header & Footer", "ultraaddons" ),
		"item_published" => __( "Header & Footer published", "ultraaddons" ),
		"item_published_privately" => __( "Header & Footer published privately.", "ultraaddons" ),
		"item_reverted_to_draft" => __( "Header & Footer reverted to draft.", "ultraaddons" ),
		"item_scheduled" => __( "Header & Footer scheduled", "ultraaddons" ),
		"item_updated" => __( "Header & Footer updated.", "ultraaddons" ),
		"parent_item_colon" => __( "Parent Header & Footer:", "ultraaddons" ),
	];

	$args = [
		"label" => __( "Header & Footer", "ultraaddons" ),
		"labels" => $labels,
		"description" => __( "This post is for Medilac Header and Footer", "ultraaddons" ),
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => false,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "header-footer", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-hammer",
		"supports" => [ "title", "editor" ],
	];

	register_post_type( "header_footer", $args );
    }
}