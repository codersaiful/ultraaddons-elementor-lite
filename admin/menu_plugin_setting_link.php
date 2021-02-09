<?php

add_filter('plugin_action_links_' . ULTRA_ADDONS_BASE_NAME, 'ua_add_action_links');

if( !function_exists( 'ua_add_action_links' ) ){
    /**
     * For showing configure or add new link on plugin page
     * It was actually an individual file, now combine at 4.1.1
     * @param type $links
     * @return type
     */
    function ua_add_action_links($links) {
        $ua_links[] = '<a href="https://codecanyon.net/item/woo-product-table-pro/20676867" title="' . esc_attr__( 'Many awesome features is waiting for you', 'ua_pro' ) . '" target="_blank">'.esc_html__( 'GET PRO VERSION','ua_pro' ).'</a>';
        $ua_links[] = '<a href="https://codeastrology.com/support/" title="' . esc_attr__( 'CodeAstrology Support', 'ua_pro' ) . '" target="_blank">'.esc_html__( 'Support','ua_pro' ).'</a>';
        $ua_links[] = '<a href="https://github.com/codersaiful/woo-product-table" title="' . esc_attr__( 'Github Repo Link', 'ua_pro' ) . '" target="_blank">'.esc_html__( 'Github Repository','ua_pro' ).'</a>';
        return array_merge( $ua_links, $links );
    }                                       
}


if( !function_exists( 'ua_admin_menu' ) ){
    /**
     * Set Menu for WPT (Woo Product Table) Plugin
     * It was actually an individual file, now combine  at 4.1.1
     * 
     * @since 1.0
     * 
     * @package Woo Product Table
     */
    function ua_admin_menu() {
        
        add_submenu_page( 'edit.php?post_type=ua_product_table', esc_html__( 'Configuration WPTpro', 'ua_pro' ),  esc_html__( 'Configure', 'ua_pro' ), WPT_CAPABILITY, 'woo-product-table-config', 'ua_configuration_page' );
        add_submenu_page( 'edit.php?post_type=ua_product_table', esc_html__( 'Contribute to our Github Repository', 'ua_pro' ), sprintf( esc_html__( 'Github %s Repo%s', 'ua_pro' ), '<span style="color:#ffff21;">', '</span>'), WPT_CAPABILITY, 'https://github.com/codersaiful/woo-product-table' );
        add_submenu_page( 'edit.php?post_type=ua_product_table', esc_html__( 'FAQ & Support page - Contact With US', 'ua_pro' ), sprintf( esc_html__( 'FAQ %s& Contact%s', 'ua_pro' ), '<span style="color:#ff8921;">', '</span>'), WPT_CAPABILITY, 'ua_fac_contact_page', 'ua_fac_support_page' );
        add_submenu_page( 'edit.php?post_type=ua_product_table', esc_html__( 'GET PRO VERSION', 'ua_pro' ),  __( '<i>Get <strong>Pro</strong></i>', 'ua_pro' ), WPT_CAPABILITY, 'https://codecanyon.net/item/woo-product-table-pro/20676867' );
        add_submenu_page( 'edit.php?post_type=ua_product_table', esc_html__( 'Pro Features', 'ua_pro' ),  __( 'Pro Features', 'ua_pro' ), 'manage_options', 'wpt-pro-features', 'ua_pro_features_content' );
        //add_submenu_page( 'edit.php?post_type=ua_product_table', esc_html__( 'Browse Plugins', 'ua_pro' ),  __( 'Browse Plugins', 'ua_pro' ), WPT_CAPABILITY, 'wpt-browse-plugins', 'ua_browse_all_plugin_list' );
    }
}
//add_action( 'admin_menu', 'ua_admin_menu' );

