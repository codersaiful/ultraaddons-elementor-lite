<?php
namespace UltraAddons\WP;

/**
 * ******************
 * TAXONOMY CUSTOM FONTS
 * ******************
 * Our Plane here: * 
 * * I will create a taxonomy for Font handle 
 * * Add Custom Field for Font variation Actually
 * 
 * 
 * 
 * @since 1.1.0.2
 */
class Custom_Fonts_Taxonomy{

    private static $instance = null;
    
    public static $meta_key = 'ua_fonts';
    public static $slug = 'ultraaddons-custom-fonts';

	/**
	 * Fonts
	 *
	 * @since  1.0.0
	 * @var (string) $fonts
	 */
	public static $fonts = null;

    /**
     * Constructor for Custom Fonts Taxonomy
     * 
     * @since 1.1.0.2
     */
    public function __construct(){
        add_action( 'init', array( $this, 'register_taxonomy' ) );
    }
	
	/**
	 * Return Instance of Custom_Fonts_Taxonomy
	 */
    public static function init() {
        
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;

    }

	public static function get_term_name(){
		return self::$slug;
	}
	
	public static function get_meta_key(){
		return self::$meta_key;
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