<?php

if( ! function_exists( 'ua_enqueue' ) ){

   function ua_enqueue(){
        wp_register_style( 'ultraaddons-elementor-admin-style', ULTRA_ADDONS_ASSETS . 'css/admin.css' );
        wp_enqueue_style( 'ultraaddons-elementor-admin-style' );
       
   }
}
//add_action( 'wp_enqueue_scripts', 'ua_enqueue' );
add_action( 'admin_enqueue_scripts', 'ua_enqueue' );