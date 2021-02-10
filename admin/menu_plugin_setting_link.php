<?php

add_filter('plugin_action_links_' . ULTRA_ADDONS_BASE_NAME, 'ultraaddons_add_action_links');

if( !function_exists( 'ultraaddons_add_action_links' ) ){
    /**
     * For showing configure or add new link on plugin page
     * It was actually an individual file, now combine at 4.1.1
     * @param type $links
     * @return type
     */
    function ultraaddons_add_action_links($links) {
        $ultraaddons_links[] = '<a href="https://codecanyon.net/item/woo-product-table-pro/20676867" title="' . esc_attr__( 'Many awesome features is waiting for you', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'GET PRO VERSION','ultraaddons_pro' ).'</a>';
        $ultraaddons_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'Support','ultraaddons_pro' ).'</a>';
        $ultraaddons_links[] = '<a href="https://github.com/codersaiful/woo-product-table" title="' . esc_attr__( 'Github Repo Link', 'ultraaddons_pro' ) . '" target="_blank">'.esc_html__( 'Github Repository','ultraaddons_pro' ).'</a>';
        return array_merge( $ultraaddons_links, $links );
    }                                       
}


if( !function_exists( 'ultraaddons_admin_menu' ) ){
    /**
     * Set Menu for WPT (Woo Product Table) Plugin
     * It was actually an individual file, now combine  at 4.1.1
     * 
     * @since 1.0
     * 
     * @package Woo Product Table
     */
    function ultraaddons_admin_menu() {
        
        add_submenu_page( 'edit.php?post_type=ultraaddons_product_table', esc_html__( 'Configuration WPTpro', 'ultraaddons_pro' ),  esc_html__( 'Configure', 'ultraaddons_pro' ), WPT_CAPABILITY, 'woo-product-table-config', 'ultraaddons_configuration_page' );
    }
}
//add_action( 'admin_menu', 'ultraaddons_admin_menu' );

