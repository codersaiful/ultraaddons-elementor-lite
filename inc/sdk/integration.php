<?php
/**
 * Pro version Integration
 * 
 * @Since 1.0.5.1
 * @by Saiful
 * @date 1.5.2021
 */
if ( ! function_exists( 'ultraaddons_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ultraaddons_fs() {
        global $ultraaddons_fs;

        if ( ! isset( $ultraaddons_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $ultraaddons_fs = fs_dynamic_init( array(
                'id'                  => '8298',
                'slug'                => 'ultraaddons-elementor-lite',
                'type'                => 'plugin',
                'public_key'          => 'pk_5530a90ed71cc7f8ccb1be12305b4',
                'is_premium'          => true,
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'           => 'ultraaddons-elementor-lite',
                    'first-path'     => 'admin.php?page=ultraaddons-elementor-lite',
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key'          => 'sk_bzh9R;h!:E~CHe<>2:2)wFXU+RykK',
            ) );
        }

        return $ultraaddons_fs;
    }

    // Init Freemius.
    ultraaddons_fs();
    // Signal that SDK was initiated.
    do_action( 'ultraaddons_fs_loaded' );
}


if( ultraaddons_fs()->is_plan( 'professional' ) ){
    include_once __DIR__ . '/pro.php';
}