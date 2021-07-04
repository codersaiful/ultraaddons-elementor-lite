<?php

defined( 'ABSPATH' ) || die();

/**
 * Getting Help your for Widget.
 * 
 * Link's prefix, I mean: first part of URL has taken from constant
 * 
 * @since 1.0.0.2
 * @by Saiful
 * 
 * @param string $class_name
 * @param type $object
 * @return string Full URL link for Class
 */
function ultraaddons_help_url( $class_name, $object = false ){
    
    /**
     * using Constant: ULTRA_ADDONS_WIDGET_HELP_ULR 
     * This constant has come from init.php file inside root directory of this plugin
     * 
     * @since 1.0.0.3
     */
    return ULTRA_ADDONS_WIDGET_HELP_ULR . $class_name;
}

if( ! function_exists( 'ultraaddons_is_cf7_activated' ) ){
    /**
    * Check if contact form 7 is activated
    *
    * @return bool
    */
   function ultraaddons_is_cf7_activated() {
           return class_exists( '\WPCF7' );
   }
}

if( !function_exists( 'ultraaddons_get_cf7_forms' ) ){   
    /**
     * Get a list of all CF7 forms
     *
     * @return array
     */
    function ultraaddons_get_cf7_forms() {
            $forms = [];

            if ( ultraaddons_is_cf7_activated() ) {
                    $_forms = get_posts( [
                            'post_type'      => 'wpcf7_contact_form',
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                    ] );

                    if ( ! empty( $_forms ) ) {
                            $forms = wp_list_pluck( $_forms, 'post_title', 'ID' );
                    }
            }

            return $forms;
    }
}

if( !function_exists( 'ultraaddons_get_current_user_display_name' ) ){
    /**
     * Get user name
     * @return type
     */
    function ultraaddons_get_current_user_display_name() {
            $user = wp_get_current_user();
            $name = 'user';
            if ( $user->exists() && $user->display_name ) {
                    $name = $user->display_name;
            }
            return $name;
    }
}
if( ! function_exists( 'ultraaddons_do_shortcode' ) ){
    /**
     * Call a shortcode function by tag name.
     *
     * @since  1.0.0
     *
     * @param string $tag     The shortcode whose function to call.
     * @param array  $atts    The attributes to pass to the shortcode function. Optional.
     * @param array  $content The shortcode's content. Default is null (none).
     *
     * @return string|bool False on failure, the result of the shortcode on success.
     */
    function ultraaddons_do_shortcode( $tag, array $atts = array(), $content = null ) {
            global $shortcode_tags;
            if ( ! isset( $shortcode_tags[ $tag ] ) ) {
                    return false;
            }
            return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }
}

/**
 * Get Elementor instance
 * 
 * It's a Object of Elementor
 * Which will be need for every widget register of Elementor Widget.
 * 
 * @since 1.0.0.2
 * @by Saiful
 *
 * @return \Elementor\Plugin Instance
 */
function ultraaddons_elementor() {
	return \Elementor\Plugin::instance();
}

/**
 * Get Boolean for Pro
 * 
 * @return bool True|False
 */
function ultraaddons_is_pro(){
//    return false; //Only for Development Perspose.
    return defined( 'ULTRA_ADDONS_PRO_VERSION' );
}

/**
 * Get Plugin's Version name.
 * For Premium, it will return pro,
 * and for free, it will return free
 * 
 * @return string free|pro
 */
function ultraaddons_plugin_version(){
    return ultraaddons_is_pro() ? 'pro' :'free';
}

/**
 * Outpur elementor page content to any where
 * Just need that template id
 * Mean: Post ID of that template
 * 
 * @param int $post_id POST Id, can be any post id. basically for Elementor Template's POSD id
 * @return boolean|String|null if not found, return false. if not set post id, return null and for success return content
 */
function ultraaddons_elementor_display_content( $post_id = false ){
    if( empty( $post_id ) || ! $post_id || ! is_numeric( $post_id ) ){
        return;
    }
    
    (int) $select_post_id = $post_id;
    if ( \Elementor\Plugin::instance()->db->is_built_with_elementor( $select_post_id ) ) {
        return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
    }
    return false;
}

function ultraaddons_icon_markup( $size = 'small' ){
    $markup = "<i class='ultraaddons ua_icon ua_icon_{$size}'></i>";
    return apply_filters( 'ultraaddons_icon_murkup', $markup );
}


function ultraaddons_get_placeholder_image_src( $image = '' ) {
        $placeholder_image = ULTRA_ADDONS_ASSETS . 'images/no-image.png';

        /**
         * Get placeholder image source.
         * 
         *
         * Filters the source of the default placeholder image used by Elementor.
         *
         * @since 1.0.0
         *
         * @param string $placeholder_image The source of the default placeholder image.
         */
        $placeholder_image = apply_filters( 'ultraaddons_get_placeholder_image_src', $placeholder_image );

        return $placeholder_image;
}

/**
* Checks a control value for being empty, including a string of '0' not covered by PHP's empty().
*
* @param mixed $source
* @param bool|string $key
*
* @return bool
*/
function ultraaddons_widget_data_is_empty( $source, $key = false ) {
       if ( is_array( $source ) ) {
               if ( ! isset( $source[ $key ] ) ) {
                       return true;
               }

               $source = $source[ $key ];
       }

       return '0' !== $source && empty( $source );
}

/**
 * Cart Link.
 *
 * Displayed a link to the cart including the number of items present and the cart total.
 *
 * @return void
 */
function ultraaddons_woocommerce_cart_link() {
        if( ! WC()->cart ) return;
        ?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'ultraaddons' ); ?>">
            <?php
            $item_cmount = WC()->cart->get_cart_contents_count();
            /* translators: number of items in the mini cart. */
            $item_count_text = _n( 'item', 'items', $item_cmount, 'ultraaddons' );
            $item_count_text = apply_filters( 'ultraaddons_item_text', $item_count_text, $item_cmount );
            if( $item_cmount > 0 ){
            ?>
            <span class="count">
                <span class="cart-count"><?php echo esc_html( $item_cmount ); ?></span>
                <span class="cart-item-text"><?php echo esc_html( $item_count_text ); ?></span>
            </span>
            <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
            <?php
            }
            ?>
        </a>
        <?php
}
if ( ! function_exists( 'ultraaddons_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function ultraaddons_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		ultraaddons_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ultraaddons_woocommerce_cart_link_fragment' );

/**
 * Finally I will creat field my custom code, currently using CMB2
 */
include_once __DIR__ . '/wp/custom-field.php';