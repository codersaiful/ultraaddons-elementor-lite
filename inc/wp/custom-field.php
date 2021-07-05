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
                    'id'            => 'ua_page_template_option',
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
                'single-product/sale-flash.php'        => __( 'Sale badge', 'ultraaddons' ),
                'single-product/title.php'   => __( 'Product Title', 'ultraaddons' ),
                'single-product/rating.php'   => __( 'Rating', 'ultraaddons' ),
                'single-product/short-description.php'   => __( 'Short Description', 'ultraaddons' ),
                'single'   => __( 'Signle', 'ultraaddons' ),
                'single'   => __( 'Signle', 'ultraaddons' ),
                'single'   => __( 'Signle', 'ultraaddons' ),
                );
            $cmb->add_field( array(
                    'name'       => __( 'Choose Template', 'ultraaddons' ),
                    'desc'       => __( 'Default widget is Free, If you want convert as Premium, Set Pro', 'ultraaddons' ),
                    'id'         => 'ua_page_template',
                    'type'       => 'select',
                    'default'    => '',
                    'sanitization_cb' => 'sanitize_text_field',
                    'options'    => $choices_topbar,
                    
            ) );
                
           
    }
}

//add_filter( 'template_include', function( $template ){
//     $new_template = locate_template( array( 'portfolio-page-template.php' ) );
//     $templatesss = get_page_template_slug();
//    //var_dump($new_template,$templatesss,$template);
//    return $template;
//} );

if( ! function_exists( 'wqpmb_locate_template_ultraaddons' ) ){
    /**
     * Template selection for Quantity Button
     * 
     * @global type $woocommerce
     * @return type Template
     */
    function wqpmb_locate_template_ultraaddons( $template, $template_name, $template_path ){
        
        
        
        $_template = ULTRA_ADDONS_DIR . 'template/woocommerce.php';
        if( 'single-product/sale-flash.php' == $template_name ){
           return $_template; 
        }

        return $template;
    }
    //add_filter( 'woocommerce_locate_template', 'wqpmb_locate_template_ultraaddons',1,3 );
}

add_action('wp_here_stay_on_wp',function(){
    $args = array(
        'post_type'     =>  'page',
        'post_status'   =>  'publish',
        'posts_per_page'=> '-1',
        'meta_query' => array(
                array(
                    'key' => 'ua_page_template',
                    //'value' => ':13112;',
                    //'compare' => 'LIKE'
                )
        ),
    );
    $query = get_posts( $args ); //  new \WP_Query($args);// ;
//    var_dump(get_the_ID());
//    var_dump($query);
    if(is_product()){
        //var_dump($query);
    }
    
    foreach($query as $qr){
        $id = $qr->ID;
        $chosent_tem_path = get_post_meta( $id, 'ua_page_template', true );
        var_dump($chosent_tem_path);
        add_filter( 'woocommerce_locate_template', function( $template, $template_name ){
//            var_dump($template_name);
            global $chosent_tem_path;
            var_dump($chosent_tem_path);
            $_template = ULTRA_ADDONS_DIR . 'template/' . $chosent_tem_path;
            //var_dump($chosent_tem_path,$template_name,$_template,validate_file($_template));
            if( $chosent_tem_path == $template_name && is_file($_template) ){
               return $_template; 
            }
            return $template;
        },1,2 );
        
        
    }
});