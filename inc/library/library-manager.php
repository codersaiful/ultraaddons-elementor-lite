<?php
namespace UltraAddons\Library;

defined('ABSPATH') || die();

class Library_Manager{
    
    public static function init(){
        add_action( 'elementor/init', [__CLASS__, 'testing'] );
        
//        add_action( 'elementor/editor/footer', [__CLASS__, 'render_panel_html'] );
    }
    
    public static function render_panel_html(){
        //var_dump(__DIR__ . '/templates/panel.php');
        include __DIR__ . '/templates/test.php';
    }
    
    public static function testing() {
                include __DIR__ . '/library-source.php';

                // Unregister source with closure binding, thank Steve.
                $unregister_source = function($id) {
                        unset( $this->_registered_sources[ $id ] );
                };
                $unregister_source->call( \Elementor\Plugin::instance()->templates_manager, 'remote');
                var_dump( \Elementor\Plugin::instance()->templates_manager );
                \Elementor\Plugin::instance()->templates_manager->register_source( 'Library_Source' );
        }
}