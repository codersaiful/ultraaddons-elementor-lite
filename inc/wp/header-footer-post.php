<?php
namespace UltraAddons\WP;

class Header_Footer_Post{
    
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_post' ],99 );
		add_action( 'add_meta_boxes', [ __CLASS__, 'register_metabox' ] );
		add_action( 'save_post', [ __CLASS__, 'save_meta' ] );

		//Now menu is handaling from admin-handle.php file
        //add_action( 'admin_menu', [ __CLASS__, 'add_submenu' ] );
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
			"publicly_queryable" => false,
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
			"supports" => [ 'title', 'thumbnail', 'elementor' ],
		];

		register_post_type( "header_footer", $args );
    }

	public static function register_metabox() {
		add_meta_box(
			'ua-meta-box',
			__( 'Header & Footer Options', 'ultraaddons' ),
			[
				__CLASS__,
				'metabox_render',
			],
			'header_footer',
			'normal',
			'high'
		);
	}

	/**
	 * Render HTML and Options Here
	 *
	 * @author Saiful Islam <codersaiful@gmail.com>
	 * @since 1.1.4.2
	 * @return void
	 */
	public static function metabox_render( $post ) {
		$values            = get_post_custom( $post->ID );
		$template_type     = isset( $values['ua_template_type'] ) ? esc_attr( $values['ua_template_type'][0] ) : '';
		$display_on_canvas = isset( $values['display-on-canvas-template'] ) ? true : false;
		// var_dump($values);


		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'ua_meta_nounce', 'ua_meta_nounce' );
		?>
		<table class="ua-options-table widefat">
			<tbody>
				<tr class="ua-options-row type-of-template">
					<td class="ua-options-row-heading">
						<label for="ua_template_type"><?php _e( 'Type of Template', 'header-footer-elementor' ); ?></label>
					</td>
					<td class="ua-options-row-content">
						<select name="ua_template_type" id="ua_template_type">
							<option value="" <?php selected( $template_type, '' ); ?>><?php _e( 'Select Option', 'header-footer-elementor' ); ?></option>
							<option value="type_header" <?php selected( $template_type, 'type_header' ); ?>><?php _e( 'Header', 'header-footer-elementor' ); ?></option>
							<option value="type_before_footer" <?php selected( $template_type, 'type_before_footer' ); ?>><?php _e( 'Before Footer', 'header-footer-elementor' ); ?></option>
							<option value="type_footer" <?php selected( $template_type, 'type_footer' ); ?>><?php _e( 'Footer', 'header-footer-elementor' ); ?></option>
							<option value="custom" <?php selected( $template_type, 'custom' ); ?>><?php _e( 'Custom Block', 'header-footer-elementor' ); ?></option>
						</select>
					</td>
				</tr>

				<?php 
				self::display_rule();
				 ?>
				<tr class="ua-options-row ua-shortcode">
					<td class="ua-options-row-heading">
						<label for="ua_template_type"><?php _e( 'Shortcode', 'header-footer-elementor' ); ?></label>
						<i class="ua-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'header-footer-elementor' ); ?>">
						</i>
					</td>
					<td class="ua-options-row-content">
						<span class="ua-shortcode-col-wrap">
							<input type="text" onfocus="this.select();" readonly="readonly" value="[hfe_template id='<?php echo esc_attr( $post->ID ); ?>']" class="ua-large-text code">
						</span>
					</td>
				</tr>
				<tr class="ua-options-row enable-for-canvas">
					<td class="ua-options-row-heading">
						<label for="display-on-canvas-template">
							<?php _e( 'Enable Layout for Elementor Canvas Template?', 'header-footer-elementor' ); ?>
						</label>
						<i class="ua-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Enabling this option will display this layout on pages using Elementor Canvas Template.', 'header-footer-elementor' ); ?>"></i>
					</td>
					<td class="ua-options-row-content">
						<input type="checkbox" id="display-on-canvas-template" name="display-on-canvas-template" value="1" <?php checked( $display_on_canvas, true ); ?> />
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	public static function display_rule( $field_title = 'Display On', $rule = 'rule' ){
		global $post;
		$post_id = $post->ID;
		$values = get_post_meta($post_id,'ua_display',true);
		$field_arr = [
			'Basic' => [
				'basic-global', 
				'basic-singulars',
				'basic-archives'
			]
		];
		
		?>
		<tr class="ua-options-row display-rule">
			<td class="ua-options-row-heading">
				<label for="ua_display_rule"><?php _e( $field_title ); ?></label>
			</td>
			<td class="ua-options-row-content">
				<select name="ua_display[<?php echo esc_attr($rule); ?>][]" class="target_rule-condition form-control ast-input" multiple>
					<option value="">Select</option>
					<optgroup label="Basic">
						<option value="basic-global" selected="selected">Entire Website</option>
						<option value="basic-singulars">All Singulars</option>
						<option value="basic-archives">All Archives</option>
					</optgroup>
					<optgroup label="Special Pages">
						<option value="special-404">404 Page</option>
						<option value="special-search">Search Page</option>
						<option value="special-blog">Blog / Posts Page</option>
						<option value="special-front">Front Page</option>
						<option value="special-date">Date Archive</option>
						<option value="special-author">Author Archive</option>
						<option value="special-woo-shop">WooCommerce Shop Page</option>
					</optgroup>
					<optgroup label="Posts">
						<option value="post|all">All Posts</option>
						<option value="post|all|archive">All Posts Archive</option>
						<option value="post|all|taxarchive|category">All Categories Archive</option>
						<option value="post|all|taxarchive|post_tag">All Tags Archive</option>
					</optgroup>
					<optgroup label="Pages">
						<option value="page|all">All Pages</option>
					</optgroup>
					<optgroup label="Landing Pages">
						<option value="e-landing-page|all">All Landing Pages</option>
						<option value="e-landing-page|all|archive">All Landing Pages Archive</option>
					</optgroup>
					<optgroup label="My Templates">
						<option value="elementor_library|all">All My Templates</option>
						<option value="elementor_library|all|archive">All My Templates Archive</option>
					</optgroup>
					<optgroup label="Products">
						<option value="product|all">All Products</option>
						<option value="product|all|archive">All Products Archive</option>
						<option value="product|all|taxarchive|product_cat">All Product Categories Archive</option>
						<option value="product|all|taxarchive|product_tag">All Product Tags Archive</option>
						<option value="product|all|taxarchive|product_shipping_class">All Product Shipping Classes Archive</option>
					</optgroup>
					
					<!-- <optgroup label="Header &amp; Footer">
						<option value="header_footer|all">All Header &amp; Footer</option>
						<option value="header_footer|all|archive">All Header &amp; Footer Archive</option>
					</optgroup> -->
					<optgroup label="Specific Target">
						<option value="specifics">Specific Pages / Posts / Taxonomies, etc.</option>
					</optgroup>
				</select>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save meta field.
	 *
	 * @param  POST $post_id Currennt post object which is being displayed.
	 *
	 * @return Void
	 */
	public static function save_meta( $post_id ) {
		// var_dump($_POST);
		// die();
		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST['ua_meta_nounce'] ) || ! wp_verify_nonce( $_POST['ua_meta_nounce'], 'ua_meta_nounce' ) ) {
			return;
		}

		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// $target_locations = Astra_Target_Rules_Fields::get_format_rule_value( $_POST, 'bsf-target-rules-location' );
		// $target_exclusion = Astra_Target_Rules_Fields::get_format_rule_value( $_POST, 'bsf-target-rules-exclusion' );
		// $target_users     = [];

		// if ( isset( $_POST['bsf-target-rules-users'] ) ) {
		// 	$target_users = array_map( 'sanitize_text_field', $_POST['bsf-target-rules-users'] );
		// }

		// update_post_meta( $post_id, 'ua_target_include_locations', $target_locations );
		// update_post_meta( $post_id, 'ua_target_exclude_locations', $target_exclusion );
		update_post_meta( $post_id, 'ua_target_user_roles', $target_users );

		if ( isset( $_POST['ua_template_type'] ) ) {
			update_post_meta( $post_id, 'ua_template_type', esc_attr( $_POST['ua_template_type'] ) );
		}
		if ( isset( $_POST['ua_display'] ) ) {
			update_post_meta( $post_id, 'ua_display', $_POST['ua_display'] );
		}

		if ( isset( $_POST['display-on-canvas-template'] ) ) {
			update_post_meta( $post_id, 'display-on-canvas-template', esc_attr( $_POST['display-on-canvas-template'] ) );
		} else {
			delete_post_meta( $post_id, 'display-on-canvas-template' );
		}
	}
}