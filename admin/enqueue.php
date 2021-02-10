<?php

if( ! function_exists( 'ultraaddons_enqueue' ) ){

   function ultraaddons_enqueue(){
        wp_register_style( 'ultraaddons-elementor-admin-style', ULTRA_ADDONS_ASSETS . 'css/admin.css' );
        wp_enqueue_style( 'ultraaddons-elementor-admin-style' );
       
   }
}
//add_action( 'wp_enqueue_scripts', 'ultraaddons_enqueue' );
add_action( 'admin_enqueue_scripts', 'ultraaddons_enqueue' );