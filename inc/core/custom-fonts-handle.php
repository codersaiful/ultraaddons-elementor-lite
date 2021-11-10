<?php
namespace UltraAddons\Core;

use UltraAddons\WP\Custom_Fonts_Taxonomy;

defined( 'ABSPATH' ) || die();

/**
 * Control of Custom_Fonts_Handle
 * To show Custom Header which is made by elementor Page Builder
 * 
 * We will handle Custom Fonts from register term 
 *
 * @author Saiful
 * @since 1.0.1.0
 */
class Custom_Fonts_Handle extends Custom_Fonts_Taxonomy {
    

    //public static $meta_key; Already declreared in parent Class/Object

    /**
     * Elementor keep font with font group.
     * We set it - ultraaddons-custom-fonts
     * 
     * It's actually come from \UltraAddons\WP\Custom_Fonts_Post
     */
    public static $font_group_key;

    public static $fonts;
    /**
     * key for update and get data from database.
     *
     * @var string option key for update and get data from database. 
     */
    //public static $key = 'ultraaddons_header_footer';
    

    public static function init() {
        self::$font_group_key = self::$slug;//self::get_font_group();

        /**
         * Add Taxonomy for Custom Field
         * AND
         * Adding custom field to Taxonomy
         */
        parent::init(); //\UltraAddons\WP\Custom_Fonts_Taxonomy::init();


        /**
         * I will handle Custom Field to adding Extra
         * Option for Each Font
         * I will add custom field for Taxonomy: Custom_Fonts
         * 
         * @since 1.1.0.3
         */
        $font_group_key = self::$font_group_key;
        add_filter( 'manage_edit-' . $font_group_key . '_columns', [__CLASS__, 'manage_columns'] );

        add_action( $font_group_key . '_add_form_fields', [__CLASS__, 'field_on_taxomoy'] ); 
        add_action( $font_group_key . '_edit_form_fields', [__CLASS__, 'edit_term_fields'], 10, 2 );//edit_term_fields
        
        // //Save Data on Submit
        add_action( 'created_' . $font_group_key, [__CLASS__,'save_term_fields'] ); 
        add_action( 'edited_' . $font_group_key, [__CLASS__,'save_term_fields'] ); 

    }

    public static function field_on_taxomoy(){
        
        ?>
        <style>.form-field.term-description-wrap,.form-field.term-slug-wrap{display: none !important;}</style>
        <div class="form-field">
            <label for="font-fallback"><?php echo esc_html__( 'Font Fallback' ); ?></label>
            <input name="ua_fonts[fallback]" type="text" id="font-fallback">
            <p></p>
        </div> 
        <?php
    }
    public static function edit_term_fields($tag,  $taxonomy){

        $meta_key = self::$meta_key;
        $data = get_term_meta($tag->term_id,$meta_key, true);
        $saiful = isset( $data['saiful'] ) ? $data['saiful'] : '';
        
        ?>
        <style>.form-field.term-description-wrap,.form-field.term-slug-wrap{display: none !important;}</style>
        <tr class="form-field">
            <th>
                <label for="table_id"><?php echo esc_html__( 'Product Table' ); ?></label>
            </th>
            <td>
                <input name="ua_fonts[saiful]" type="text" value="<?php echo $saiful; ?>">
                <p></p>
            </td>
        </tr>
        <?php
    }

    /**
     * To Create new term on font taxonomy
     * Also to update
     * 
     * I will use this method
     * 
     * @param $term_id Available This termn, wen click update and add new
     * 
     * @since 1.1.0.3
     */
    public static function save_term_fields( $term_id ){
        
        if( isset( $_POST[self::$meta_key] ) && is_array( $_POST[self::$meta_key] ) ){
            $meta_value = $_POST[self::$meta_key];
            update_term_meta( $term_id, self::$meta_key, $meta_value );
        }
    }

    /**
     * Manage Columns Using wp Action hook
     *
     * @since 1.1.0.3
     * @param array $columns default columns.
     * @return array $columns updated columns.
     */
    public static function manage_columns( $columns ) {

        $screen = get_current_screen();
        // If current screen is add new custom fonts screen.
        if ( isset( $screen->base ) && 'edit-tags' == $screen->base ) {

            $old_columns = $columns;
            $columns     = array(
                'cb'   => $old_columns['cb'],
                'name' => esc_html__( 'Font Name', 'ultraaddons' ),
            );

        }
        return $columns;
    }


    /**
     * In parent class,
     * method was get_term_name
     * and property was self::$slug;
     * 
     * but here need $font_group
     * 
     * and we set it in __construct
     * 
     * @since 1.1.0.3
     */
    public static function get_font_group(){
        return self::get_term_name();
    }

    
    /**
     * Get fonts
     *
     * @since 1.0.0
     * @return array $fonts fonts array of fonts.
     */
    public static function get_fonts() {

        if ( is_null( self::$fonts ) ) {

            self::$fonts = array();
            $args = array(
                'hide_empty' => false
            );
            $term_name = self::get_font_group();
            $terms = get_terms( $term_name, $args );

            if ( ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    self::$fonts[ $term->name ] = $term_name;
                }
            }

        }
        return self::$fonts;
    }
    
}

//Custom_Fonts_Handle::init();