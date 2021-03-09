<?php
namespace UltraAddons\Library;

defined('ABSPATH') || die();

class Library_Manager{
    public static function init(){
//        var_dump(__DIR__ . '/templates/panel.php');
        add_action( 'elementor/editor/footer', [ __CLASS__, 'render_panel_html' ] );
    }
    
    public static function render_panel_html(){
        include __DIR__ . '/templates/panel.php';
    }
}