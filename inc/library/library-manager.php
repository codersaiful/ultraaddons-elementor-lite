<?php
namespace UltraAddons\Library;

defined('ABSPATH') || die();

class Library_Manager{
    public static function init(){
//        var_dump(__DIR__ . '/templates/panel.php');
//        add_action( 'elementor/editor/footer', [ __CLASS__, 'render_panel_html' ] );
        //add_action( 'elementor/editor/footer', [ __CLASS__, 'render_test_html' ] );
        //add_action( 'wp_footer', [ __CLASS__, 'render_test_html' ] );
        
//        add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
//        add_action( 'elementor/editor/after_save', [ __CLASS__, 'testing_action_hook' ], 10, 2 );
    }
    
    public static function render_panel_html(){

        include __DIR__ . '/templates/panel.php';
    }
    
    
    public static function render_test_html(){
        var_dump(self);
        include __DIR__ . '/templates/test.php';
    }
    
    public static function register_ajax_actions( Ajax $ajax ) {
        var_dump($ajax);
    }

    
    public static function testing_action_hook( $params, $params2 ) {
        var_dump($params, $params2);
    }

    
}