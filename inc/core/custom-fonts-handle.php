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

        <div class="form-field">
            <label for="font-display"><?php echo esc_html__( 'Font Display' ); ?></label>
            <?php self::render_font_dsplay(); ?>
            <p></p>
        </div> 

        <?php 
        /**
         * Render font variations markup from a method
         * 
         * @since 1.1.0.5
         * 
         */
        self::render_fonts_variant(); 
    }
    public static function edit_term_fields($tag,  $taxonomy){

        $meta_key = self::$meta_key;
        $data = get_term_meta($tag->term_id,$meta_key, true);
        //var_dump($data);
        
        $font_fallback = isset( $data['fallback'] ) ? $data['fallback'] : '';
        $font_display = isset( $data['display'] ) ? $data['display'] : '';
        

        
        ?>
        <style>.form-field.term-description-wrap,.form-field.term-slug-wrap{display: none !important;}</style>
        <tr class="form-field">
            <th>
            <label for="font-fallback"><?php echo esc_html__( 'Font Fallback' ); ?></label>
            </th>
            <td>
                <input name="ua_fonts[fallback]" type="text" id="font-fallback" value="<?php echo esc_attr( $font_fallback ); ?>">
            </td>
        </tr>


        <tr class="form-field">
            <th>
                <label for="font-display"><?php echo esc_html__( 'Font Display' ); ?></label>
            </th>
            <td>
                <?php self::render_font_dsplay( $font_display ); ?>
                <p></p>
            </td>
        </tr>
        <tr class="form-field">
            <th>
                
            </th>
            <td>
                
                <?php
                /**
                 * Render font variations markup from a method
                 * 
                 * @since 1.1.0.5
                 * 
                 */
                self::render_fonts_variant( $data ); 
                ?>

            </td>
        </tr>


        <?php
    }


    public static function render_fonts_variant( $data = array() ){
        
        $variants = array(
            array(
                'weight' => 400,
                'format' => array(''),
                'url'       => array(''),
            ),
        );


        if( isset( $data['variants'] )  && ! empty( $data['variants'] ) ){
            $variants = $data['variants'];
        }

        
        $count = count( $variants );
        echo '<div class="all-variant-group-wrapper" data-count="'. $count .'">';
        $variant_key = 0;
        foreach( $variants as $variant ){

            
            $name_prefix = "ua_fonts[variants][$variant_key]";

            $font_weight = isset( $variant['weight'] ) ? $variant['weight'] : 400;

            $urls = isset( $variant['url'] ) ? $variant['url'] : array('');

            ?>

            
            <div class="font-variation-wrapper" data-variant_key="<?php echo esc_attr( $variant_key ); ?>">
                <span class="ua-close-variant"><i><?php echo esc_html__( 'Delete Variant', 'ultraaddons' ); ?> </i>&#9986;</span>
                <div class="form-field">
                    <label for="font-weight-<?php echo esc_attr( $variant_key ); ?>"><?php echo esc_html__( 'Font Weight' ); ?></label>
                    <?php self::render_font_weight( $font_weight, $name_prefix . '[weight]', 'font-weight-' . $variant_key ); ?>
                    <p class="ua-field-notice"><?php echo esc_html__( 'Font weight for this variant.' ); ?></p>
                </div> 
                
                <div class="fonts-upload-wrapper form-field">
                    <label><?php echo esc_html__( 'Font File Upload' ); ?> <span class="font-upload-add-font-button">+ add new font file</span></label>
                    
                    <div class="fonts-upload-wrapper-inside">
                    <?php
                    foreach( $urls as $key=>$url ){
                        $format = isset( $variant['format'][$key] ) ? $variant['format'][$key] : '';
                    ?>
                    <div class="form-file-field font-file-each-wrapper">
                        
                        <input name="<?php echo esc_attr( $name_prefix ); ?>[format][]" type="hidden" class="font-upload-format" value="<?php echo esc_attr( $format ); ?>">
                        <input name="<?php echo esc_attr( $name_prefix ); ?>[url][]" type="text" value="<?php echo esc_attr( $url ); ?>" class="font-upload-url" id="font-url-<?php echo esc_attr( $variant_key ); ?>" placeholder="<?php echo esc_attr( 'Font file URL...','ultraaddons' ); ?>">
                        <a href="#" class="ultraaddons-font-upload-button ua-button button">Upload Font</a>
                    </div> 

                    <?php    
                    }
                    
                    ?>
                    </div>
                    <p class="ua-field-notice"><?php echo esc_html__( 'Upload your webfonts. Supported font type/format: woff2,woff,ttf etc so on.' ); ?></p>

                </div>

            </div>

            <?php

            $variant_key++;            
        }
        
        echo '</div>';
        echo '<span class="ua-add-new-variant" id="ua-add-new-variant">+ Add new variant</span>';
    }

    /**
     * Render selectt and option tag markup for font display
     *
     * @param String $current_value
     * @return void
     */
    public static function render_font_dsplay( $current_value = null ){
        
        $options = $default = array(
            'auto'     => 'auto',
            'block'    => 'block',
            'swap'     => 'swap',
            'fallback' => 'fallback',
            'optional' => 'optional',
        );
        $options = apply_filters( 'ultraaddons/custom_fonts/font_display', $options );

        $options = wp_parse_args( $options, $default );

        self::rennder_select( $options, $current_value, 'ua_fonts[display]', 'font-display' );
        //self::rennder_select( $options, null, 'ua_fonts[fallback]', 'font-display' );

    }
    
    /**
     * Render selectt and option tag markup for font weight
     * 
     * Options:
     *  <option value="100">Thin 100</option>
        <option value="200" selected="selected">Extra-Light 200</option>
        <option value="300">Light 300</option>
        <option value="400">Normal 400</option>
        <option value="500">Medium 500</option>
        <option value="600">Semi-Bold 600</option>
        <option value="700">Bold 700</option>
        <option value="800">Extra-Bold 800</option>
        <option value="900">Ultra-Bold 900</option>
     *
     * @param Srging $name  Actually field name, need for form submission
     * @param String $tag_id_selector id selector attribute, Need for label tag
     * @param String $current_value
     * @return void
     */
    public static function render_font_weight( $current_value = null, $name = 'ua_fonts[variants][0][weight]', $tag_id_selector  ){
        
        $options = $default = array(
            '100'     => 'Thin 100',
            '200'     => 'Extra-Light 200',
            '300'     => 'Light 300',
            '400'     => 'Normal 400',
            '500'     => 'Medium 500',
            '600'     => 'Semi-Bold 600',
            '700'     => 'Bold 700',
            '800'     => 'Extra-Bold 800',
            '900'     => 'Ultra-Bold 900',
        );
        $options = apply_filters( 'ultraaddons/custom_fonts/font_display', $options );

        /**
         * Actually when I passed data over wp_parse_args() 
         * Array is changing as numeric array
         * but we need associative array
         * 
         * So I removed wp_parse_args validation.
         */
        //$options = wp_parse_args( $options, $default );

        //As I removed wp_parse_args() I checked it over if statement
        $options = is_array( $options ) ? $options : array();

        self::rennder_select( $options, $current_value, $name, $tag_id_selector );
        //self::rennder_select( $options, $current_value, 'ua_fonts[weight]', 'font-weight' );
        //self::rennder_select( $options, null, 'ua_fonts[fallback]', 'font-display' );

    }


    /**
     * Render select and option tag, based on a array<br>
     * ARRAY SHOULD BE LIKE BELLOW:
     * array(
            'auto'     => 'auto',
            'block'    => 'block',
            'swap'     => 'swap',
            'fallback' => 'fallback',
            'optional' => 'optional',
        )
     *
     * @param array $options array of options.
     * @param String $checked Default or selected option_value
     * @param String $name form's filed name, which need to save data on database
     * @param String $tag_id its tag's id attribute
     * @return void
     */
    public static function rennder_select( $options, $current_value = null, $name=null,  $tag_id=null ){

        if( ! is_array( $options ) ) return"";

        ?>
        <select id="<?php echo esc_attr( $tag_id ); ?>" name="<?php echo esc_attr( $name ); ?>">
        <?php
        
        foreach($options as $option_key => $option_value){
            $checked = $current_value == $option_key ? 'selected' : '';
            ?>
            <option value="<?php echo esc_attr( $option_key ); ?>" <?php echo esc_attr( $checked ); ?>><?php echo esc_html( $option_value ); ?></option>
            <?php
        }

        ?>
        </select>
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
        $term = get_term_by('term_id',$term_id,self::$font_group_key);
        $font_name = $term->name;
        $trangient_name = "ua_font_trangient_" . $font_name;
        if( isset( $_POST[self::$meta_key] ) && is_array( $_POST[self::$meta_key] ) ){
            $meta_value = $_POST[self::$meta_key];
            update_term_meta( $term_id, self::$meta_key, $meta_value );
            set_transient( $trangient_name, $fonts_args );
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