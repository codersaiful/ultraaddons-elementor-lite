<?php
namespace UltraAddons\Library;

defined('ABSPATH') || die();

class Library_Manager{
    
    /**
     * Initializing Library Manager
     * Steps:
     * register sourche
     * 
     * 
     * **********************
     * Actually we added it at the begining of plugin
     * but working after 1.0.7.1
     * 
     * @package UltraAddons
     */
    public static function init(){
        add_action( 'elementor/init', [__CLASS__, 'register_source'], 15 );
    }
    
    /**
     * Register Source
     * and adding our Template at existing Template
     * 
     * @since 1.0.7.1
     * @date 24.6.2021 (d.m.y)
     * @author Saiful Islam<codersaiful@gmail.com>
     */
    public static function register_source(){
        // Unregister source with closure binding, thank Steve.
       $unregister_source = function($id) {
               unset( $this->_registered_sources[ $id ] );
       };

       $unregister_source->call( \Elementor\Plugin::instance()->templates_manager, 'remote');
       \Elementor\Plugin::instance()->templates_manager->register_source( 'UltraAddons\Library\Library_Source' );
    }
    
    
}






/**
 * All Backup Code for now, 
 * I will remove bottom all code
 * very asap
 * 
 */
//$dddd = new UltraAddons\Library\Library_Source();
//
//add_action( 'elementor/init', function() {
//       // Unregister source with closure binding, thank Steve.
//       $unregister_source = function($id) {
//               unset( $this->_registered_sources[ $id ] );
//       };
//
//       $unregister_source->call( \Elementor\Plugin::instance()->templates_manager, 'remote');
//       \Elementor\Plugin::instance()->templates_manager->register_source( 'UltraAddons\Library\Library_Source' );
//}, 15 );

class Library_Manager_Backup{
    
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