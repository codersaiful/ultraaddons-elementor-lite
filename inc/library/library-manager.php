<?php
namespace UltraAddons\Library;

defined('ABSPATH') || die();

class Library_Manager{
    
    public static function init(){
        
//        \Elementor\Plugin::instance()->templates_manager->register_source( '\UltraAddons\Library\Library_Source' );
        
        
//        var_dump(get_option('ua_library_data'));
//        update_option( 'ua_library_data', get_option('ha_library_cache') );
        add_action( 'elementor/init', [__CLASS__, 'testing'] );

//        add_action( 'elementor/editor/footer', [__CLASS__, 'render_panel_html'] );
    }
    
    public static function render_panel_html(){
        //var_dump(__DIR__ . '/templates/panel.php');
        include __DIR__ . '/templates/test.php';
    }
    
    public static function testing() {
        \Elementor\Plugin::instance()->templates_manager->unregister_source('remote');
//        var_dump(\Elementor\Plugin::instance()->templates_manager->get_registered_sources());
//        var_dump(Library_Source::get_library_data());
//        var_dump(Library_Source::$api_info_url);
//        var_dump(Library_Source::get_library_data(true));

                
//                \Elementor\Plugin::instance()->templates_manager->unregister_source( 'remote' );
                \Elementor\Plugin::instance()->templates_manager->register_source( '\UltraAddons\Library\Library_Source' );
//                var_dump(\Elementor\Plugin::instance()->templates_manager->get_registered_sources());
//                \Elementor\Plugin::instance()->templates_manager->register_source( '\UltraAddons\Library' );
                //var_dump(\Elementor\Plugin::instance()->templates_manager->_registered_sources);//$_registered_sources
                
        }
}