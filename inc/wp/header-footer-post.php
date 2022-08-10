<?php
namespace UltraAddons\WP;

use UltraAddons;
use UltraAddons\Classes\Template_List;
use UltraAddons\Core\Header_Footer;
class Header_Footer_Post{
    public static $post_type = 'header_footer';
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_post' ],99 );
		add_action( 'add_meta_boxes', [ __CLASS__, 'register_metabox' ] );
		add_action( 'save_post', [ __CLASS__, 'save_meta' ] );
		add_action( 'trashed_post', [ __CLASS__, 'delete_post' ] );
		add_action( 'delete_post', [ __CLASS__, 'delete_post' ] );

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
        if( $type == self::$post_type ){
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
        $menu_slug = admin_url( 'edit.php?post_type=' . self::$post_type );
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
			"supports" => [ 'title', 'thumbnail', 'elementor' ],
		];

		register_post_type( self::$post_type, $args );
    }

	public static function register_metabox() {
		add_meta_box(
			'ua-meta-box',
			__( 'Header & Footer Options', 'ultraaddons' ),
			[
				__CLASS__,
				'metabox_render',
			],
			self::$post_type,
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
						<label for="ua_template_type"><?php _e( 'Type of Template', 'ultraaddons' ); ?></label>
					</td>
					<td class="ua-options-row-content">
						<select name="ua_template_type" id="ua_template_type">
							<option value="" <?php selected( $template_type, '' ); ?>><?php _e( 'Select Option', 'ultraaddons' ); ?></option>
							<option value="header" <?php selected( $template_type, 'header' ); ?>><?php _e( 'Header', 'ultraaddons' ); ?></option>
							<option value="before_header" <?php selected( $template_type, 'before_header' ); ?>><?php _e( 'Before Header/Topbar', 'ultraaddons' ); ?></option>
							<option value="footer" <?php selected( $template_type, 'footer' ); ?>><?php _e( 'Footer', 'ultraaddons' ); ?></option>
							<option value="before_footer" <?php selected( $template_type, 'before_footer' ); ?>><?php _e( 'Before Footer', 'ultraaddons' ); ?></option>
							<option value="after_footer" <?php selected( $template_type, 'after_footer' ); ?>><?php _e( 'After Footer', 'ultraaddons' ); ?></option>
							<!-- <option value="custom" <?php selected( $template_type, 'custom' ); ?>><?php _e( 'Custom Block', 'ultraaddons' ); ?></option> -->
						</select>
					</td>
				</tr>

				<?php 
				self::display_rule();
				 ?>
				<tr class="ua-options-row ua-shortcode">
					<td class="ua-options-row-heading">
						<label for="ua_template_type"><?php _e( 'Shortcode', 'ultraaddons' ); ?></label>
						<i class="ua-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'ultraaddons' ); ?>">
						</i>
					</td>
					<td class="ua-options-row-content">
						<span class="ua-shortcode-col-wrap">
							<input type="text" 
							onfocus="this.select();" 
							readonly="readonly" 
							style="width: 300px;max-width:100%;"
							value="[UltraAddons_Template id='<?php echo esc_attr( $post->ID ); ?>']" 
							class="ua-large-text code">
						</span>
					</td>
				</tr>
				<tr class="ua-options-row enable-for-canvas">
					<td class="ua-options-row-heading">
						<label for="display-on-canvas-template">
							<?php _e( 'Enable Layout for Elementor Canvas Template?', 'ultraaddons' ); ?>
						</label>
						<i class="ua-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Enabling this option will display this layout on pages using Elementor Canvas Template.', 'ultraaddons' ); ?>"></i>
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
		
		$rules_arr = $values[$rule] ?? array();
		// var_dump($rules_arr);
		// return;
		$field_arr = [
			'Basic' => [

				'entire_site' => __( 'Entire Website', 'ultraaddons' ), 
				'singular' => __( 'All Singulars', 'ultraaddons' ),
				'archives' => __( 'All Archives', 'ultraaddons' ),
			],
			
			'Special Pages'=>[

				'special-404' => __( '404 Page', 'ultraaddons' ),
				'special-search' => __( 'Search Page', 'ultraaddons' ),
				'special-blog' => __( 'Blog / Posts Page', 'ultraaddons' ),
				'special-front' => __( 'Front Page', 'ultraaddons' ),
				'special-date' => __( 'Date Archive', 'ultraaddons' ),
				'special-author' => __( 'Author Archive', 'ultraaddons' ),
				'special-woo-shop' => __( 'WooCommerce Shop Page', 'ultraaddons' ),
			],

			'Posts'=>[

				'post|all' => __( 'All Posts', 'ultraaddons' ),
				'post|all|archive' => __( 'All Posts Archive', 'ultraaddons' ),
				'post|all|taxarchive|category' => __( 'All Categories Archive', 'ultraaddons' ),
				'post|all|taxarchive|post_tag' => __( 'All Tags Archive', 'ultraaddons' ),

			],

			'Pages'=>[

				'page|all' => __( 'All Pages', 'ultraaddons' ),

			],

			'Landing Pages'=>[

				'e-landing-page|all' => __( 'All Landing Pages', 'ultraaddons' ),
				'e-landing-page|all|archive' => __( 'All Landing Pages Archive', 'ultraaddons' ),

			],

			'My Templates'=>[

				'elementor_library|all' => __( 'All My Templates', 'ultraaddons' ),
				'elementor_library|all|archive' => __( 'All My Templates Archive', 'ultraaddons' ),

			],

			'Products'=>[

				'product|all' => __( 'All Products', 'ultraaddons' ),
				'product|all|archive' => __( 'All Products Archive', 'ultraaddons' ),
				'product|all|taxarchive|product_cat' => __( 'All Product Categories Archive', 'ultraaddons' ),
				'product|all|taxarchive|product_tag' => __( 'All Product Tags Archive', 'ultraaddons' ),
				'product|all|taxarchive|product_shipping_class' => __( 'All Product Shipping Classes Archive', 'ultraaddons' ),

			],

			'Specific Target'=>[

				'specifics' => __( 'Specific Pages / Posts / Taxonomies, etc.', 'ultraaddons' ),

			]

		];
		
		
		?>
		<tr class="ua-options-row display-rule">
			<td class="ua-options-row-heading">
				<label for="ua_display_rule"><?php _e( $field_title ); ?></label>
			</td>
			<td class="ua-options-row-content">
				<select name="ua_display[<?php echo esc_attr($rule); ?>][]" class="ua-target_rule-condition form-control ast-input" multiple>
					
					<?php
					foreach( $field_arr as $field_key => $field ){
						if( is_array( $field ) ){
							
							
							?>
							<optgroup label="<?php echo esc_attr( $field_key ); ?>">
							<?php
							foreach( $field as $each_key => $each_item){
								$selected = in_array($each_key, $rules_arr) ? 'selected' : '';
								?>
								<option <?php echo esc_attr( $selected );?> 
								value="<?php echo esc_attr( $each_key ); ?>">
									<?php echo esc_attr( $each_item ); ?>
								</option>
								<?php 
							}
							?>
							</optgroup>
							<?php	
						}

					}
					?>
					
					
				</select>
			</td>
		</tr>
		<?php
	}

	/**
	 * Update option for header_footer template/ and other data
	 * Actually Our template header-footer wise
	 * Optimized value save on wp_option
	 * using update_option()
	 * 
	 * 
	 *
	 * @return void
	 */
	public static function update_option(){
		
		$args = [
            'post_type'     => self::$post_type,
            'post_status'   => 'publish',
            'meta_query'    => [
                [
                    'key'   => 'ua_template_type',
                    // 'value'=> 'footer',
                ]
            ],
        ];

        $posts = query_posts($args);
        $f_post = [];
        foreach( $posts as $each_post ){
            $post_id = $each_post->ID;
			$position_name = get_post_meta($post_id,'ua_template_type',true);
			if(empty( $position_name )) continue;
            $position['position'] = $position_name;
			$display = get_post_meta($post_id,'ua_display',true);
			
			$merge = array_merge($display,$position);
            $f_post[$post_id] = $merge;
            
        }
        $f_post = ultraaddons_optimize_array($f_post);
		
        update_option(Header_Footer::$key, $f_post);
        
        wp_reset_postdata();
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
		// update_post_meta( $post_id, 'ua_target_user_roles', $target_users );

		if ( isset( $_POST['ua_template_type'] ) ) {
			update_post_meta( $post_id, 'ua_template_type', esc_attr( $_POST['ua_template_type'] ) );
		}
		if ( isset( $_POST['ua_display'] ) ) {
			$display = $_POST['ua_display'];
			
			update_post_meta( $post_id, 'ua_display', $display );
		}else{
			update_post_meta( $post_id, 'ua_display', [] );
		}

		if ( isset( $_POST['display-on-canvas-template'] ) ) {
			update_post_meta( $post_id, 'display-on-canvas-template', esc_attr( $_POST['display-on-canvas-template'] ) );
		} else {
			delete_post_meta( $post_id, 'display-on-canvas-template' );
		}

		//Update on WP Option
		self::update_option();
	}
	public static function delete_post( $post_id ) {
		
		
		// ONly for our header_footer post type
		if ( get_post_type() !== self::$post_type ) {
			return;
		}
		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		//Update on WP Option
		self::update_option();

	}
}