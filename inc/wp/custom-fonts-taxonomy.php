<?php
namespace UltraAddons\WP;

/**
 * I will create a taxonomy for Font handle 
 * 
 * @since 1.1.0.2
 */
class Custom_Fonts_Taxonomy{

    private static $instance = null;
    
    public static $slug = 'ultraaddons-custom-fonts';

    /**
     * Constructor for Custom Fonts Taxonomy
     * 
     * @since 1.1.0.2
     */
    public function __construct(){
        add_action( 'init', array( $this, 'register_taxonomy' ) );
    }
	
    public static function init() {
        
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;

    }

    public function register_taxonomy(){
        
			// Taxonomy: bsf_custom_fonts.
			$labels = array(
				'name'              => __( 'Custom Fonts', 'ultraaddons' ),
				'singular_name'     => __( 'Font', 'ultraaddons' ),
				'menu_name'         => _x( 'Custom Fonts', 'Admin menu name', 'ultraaddons' ),
				'search_items'      => __( 'Search Fonts', 'ultraaddons' ),
				'all_items'         => __( 'All Fonts', 'ultraaddons' ),
				'parent_item'       => __( 'Parent Font', 'ultraaddons' ),
				'parent_item_colon' => __( 'Parent Font:', 'ultraaddons' ),
				'edit_item'         => __( 'Edit Font', 'ultraaddons' ),
				'update_item'       => __( 'Update Font', 'ultraaddons' ),
				'add_new_item'      => __( 'Add New Font', 'ultraaddons' ),
				'new_item_name'     => __( 'New Font Name', 'ultraaddons' ),
				'not_found'         => __( 'No fonts found', 'ultraaddons' ),
				'back_to_items'     => __( '← Go to Fonts', 'ultraaddons' ),
			);

			$args = array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'public'            => false,
				'show_in_nav_menus' => false,
				'show_ui'           => true,
				'capabilities'      => array( ULTRA_ADDONS_CAPABILITY ),
				'query_var'         => false,
				'rewrite'           => false,
			);

			register_taxonomy(
				self::$slug,
				apply_filters( 'ultraaddons_taxonomy_objects_custom_fonts', array() ),
				apply_filters( 'ultraaddons_taxonomy_args_custom_fonts', $args )
			);
    }

    

}