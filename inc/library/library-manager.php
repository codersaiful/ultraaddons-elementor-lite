<?php
namespace UltraAddons\Library;

use Elementor\Plugin;

defined('ABSPATH') || die();

class Library_Manager{
    private static $library_assets = ULTRA_ADDONS_URL . 'inc/library/assets/';

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
        //ultraaddons_elementor();
        #add_action( 'elementor/init', [__CLASS__, 'register_source'], 15 );
        #add_action( 'elementor/editor/footer', [__CLASS__, 'render_panel_html'] );
        
        //var_dump(self::$library_assets);
        /** 
         * Enqueue
         */
        
        // enqueue editor js for elementor.
        add_action( 'elementor/editor/before_enqueue_scripts', [ __CLASS__, 'editor_scripts' ], 1);

        // print views and tab variables on footer.
        add_action( 'elementor/editor/footer', [ __CLASS__, 'admin_inline_js' ] );
        add_action( 'elementor/editor/footer', [ __CLASS__, 'print_views' ] );
        
        // enqueue editor css.
        add_action( 'elementor/editor/after_enqueue_styles', [ __CLASS__, 'editor_styles' ] );

        // enqueue modal's preview css.
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'preview_styles' ] );

    }
    
    public static function editor_scripts(){
        wp_enqueue_script( 
			'ultraaddons-library-editor-script', 
			self::$library_assets . 'js/editor.js', 
			array('jquery', 'underscore', 'backbone-marionette'), 
			ULTRA_ADDONS_VERSION,
			true
		);
    }
    public static function admin_inline_js(){
        ?>
		<script type="text/javascript" >

		var ElementsKitLibreryData = {
			"libraryButton": "Elements Button",
			"modalRegions": {
				"modalHeader": ".dialog-header",
				"modalContent": ".dialog-message"
			},
			"license": {
				"activated": true,
				"link": ""
			},
			"tabs": {
				"elementskit_page": {
					"title": "Ready Pages",
					"data": [],
					"sources": ["elementskit-theme", "elementskit-api"],
					"settings": {
						"show_title": true,
						"show_keywords": true
					}
				},
				"elementskit_header": {
					"title": "Headers",
					"data": [],
					"sources": ["elementskit-theme", "elementskit-api"],
					"settings": {
						"show_title": false,
						"show_keywords": true
					}
				},
				"elementskit_footer": {
					"title": "Footers",
					"data": [],
					"sources": ["elementskit-theme", "elementskit-api"],
					"settings": {
						"show_title": false,
						"show_keywords": true
					}
				},
				"elementskit_section": {
					"title": "Sections",
					"data": [],
					"sources": ["elementskit-theme", "elementskit-api"],
					"settings": {
						"show_title": false,
						"show_keywords": true
					}
				},
				"elementskit_widget": {
					"title": "Widget Presets",
					"data": [],
					"sources": ["elementskit-theme", "elementskit-api"],
					"settings": {
						"show_title": false,
						"show_keywords": true
					}
				},
				// "local": {
				// 	"title": "My Library",
				// 	"data": [],
				// 	"sources": ["elementskit-local"],
				// 	"settings": []
				// }
			},
			"defaultTab": "elementskit_page"
		};

		</script> <?php
    }
    public static function print_views(){
        
    }
    public static function editor_styles(){
        wp_enqueue_style( 'elementskit-library-editor-style', self::$library_assets . 'css/editor.css', array(), ULTRA_ADDONS_VERSION);
    }
    public static function preview_styles(){
        wp_enqueue_style( 'elementskit-library-preview-style', self::$library_assets . 'css/preview.css', array(), ULTRA_ADDONS_VERSION );
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
       
//       $hell = Plugin::instance()->templates_manager->get_registered_sources();
//       var_dump($hell['remote']);
       
       //Unregister Default/existing Template of remote
       $unregister_source->call( Plugin::instance()->templates_manager, 'remote');
       Plugin::instance()->templates_manager->register_source( 'UltraAddons\Library\Library_Source' );
    }
    
    public static function render_panel_html(){
        include_once __DIR__ . '/templates.php';
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
//       $unregister_source->call( Plugin::instance()->templates_manager, 'remote');
//       Plugin::instance()->templates_manager->register_source( 'UltraAddons\Library\Library_Source' );
//}, 15 );

class Library_Manager_Backup{
    
    public static function init(){
        
//        Plugin::instance()->templates_manager->register_source( '\UltraAddons\Library\Library_Source' );
        
        
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
        Plugin::instance()->templates_manager->unregister_source('remote');
//        var_dump(Plugin::instance()->templates_manager->get_registered_sources());
//        var_dump(Library_Source::get_library_data());
//        var_dump(Library_Source::$api_info_url);
//        var_dump(Library_Source::get_library_data(true));

                
//                Plugin::instance()->templates_manager->unregister_source( 'remote' );
                Plugin::instance()->templates_manager->register_source( '\UltraAddons\Library\Library_Source' );
//                var_dump(Plugin::instance()->templates_manager->get_registered_sources());
//                Plugin::instance()->templates_manager->register_source( '\UltraAddons\Library' );
                //var_dump(Plugin::instance()->templates_manager->_registered_sources);//$_registered_sources
                
        }
}