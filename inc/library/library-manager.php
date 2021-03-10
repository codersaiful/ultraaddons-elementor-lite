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
                
                \Elementor\Plugin::instance()->templates_manager->unregister_source( 'remote' );
                \Elementor\Plugin::instance()->templates_manager->register_source( 'Library_Source' );
                
        }
}