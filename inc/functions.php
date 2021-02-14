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