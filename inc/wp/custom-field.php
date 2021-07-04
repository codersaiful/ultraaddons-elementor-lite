<?php

/**
 * Custom field for page
 * 
 * I will create custom field for page primarily
 * 
 * and 
 * to set page template for 
 * wp-archive
 * wp-single page,
 * wp-single.php 
 * as well as 
 * wc different template
 * 
 * @since 1.0.7.21
 */

add_action( 'cmb2_admin_init', 'ultraaddons_cmb2_metaboxes' );
if( ! function_exists( 'ultraaddons_cmb2_metaboxes' )){
    
    /**
     * Page Options for Specific Page, Taxonomy, ETC
     * 
     * We have used CMB2
     */
    function ultraaddons_cmb2_metaboxes() {
            $ultraaddons_object_types = array( 'page' );

            /**
             * For Page Options
             */
            $cmb = new_cmb2_box( array(
                    'id'            => 'ua-page-template-option',
                    'title'         => __( 'UltraAddons Template', 'ultraaddons' ),
                    'object_types'  => $ultraaddons_object_types, // Post type
                    'context'       => 'normal',
                    'priority'      => 'high',
                    'show_names'    => true, // Show field names on the left
                    // 'cmb_styles' => false, // false to disable the CMB stylesheet
                    'closed'     => true, // Keep the metabox closed by default
            ) );
            //Layout Topbar
            $choices_topbar = array(
                ''              => __( 'Not for Template', 'ultraaddons' ),
                'single'        => __( 'Single Page', 'ultraaddons' ),
                'woocommerce'   => __( 'WooCommerce', 'ultraaddons' ),
                );
            $cmb->add_field( array(
                    'name'       => __( 'Choose Template', 'ultraaddons' ),
                    'desc'       => __( 'Default widget is Free, If you want convert as Premium, Set Pro', 'ultraaddons' ),
                    'id'         => 'ua-page-template',
                    'type'       => 'select',
                    'default'    => '',
                    'sanitization_cb' => 'sanitize_text_field',
                    'options'    => $choices_topbar,
                    
            ) );
                
           
    }
}