<?php
namespace UltraAddons\Library\Demo;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use UltraAddons\Core\Widgets_Manager;
use UltraAddons\Library\Demo\Theme_Demo as DemoTheme_Demo;
use UltraAddons\Library\Demo\Theme_Demo;

defined('ABSPATH') || die();

/**
 * UltraAddons Library Manager
 * Handle Full Library From Here
 * 
 * Actually We will handle Library Using Elementor Default Library Handler
 * 
 * @since 1.0.0
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Demo_Library_Manager {

	protected static $source = null;
    const TEMPLATE_ASSETS = ULTRA_ADDONS_URL . 'inc/library/demo/assets/';
	private static $theme_demo;

        public static function init() {

		self::$theme_demo = Theme_Demo::get_demo_info();
		self::$theme_demo['button']['icon'] = self::$theme_demo['button']['icon'] ?? 'uicon-ultraaddons';
		/**
		 * Onlye Developer Perpose
		 * For Developer, Unregister curent cache
		 * 
		 * 
		 * 
		 *****************************************
			* IF WANT TO DESTROY CACHE, JUST ENABLE BOTTOM ACTION. 
			* just write a slash at the end of bottom start
			******************************************
		update_option( 'eldm_library_cache', false );
		//**************************************************/
                
		add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
		add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
                
                // Enqueue editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
                
                // enqueue modal's preview css.
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'preview_styles' ] );
	}

	public static function print_template_views() {
		$tmplate_file = __DIR__ . '/templates/template.php';
		include_once $tmplate_file;
	}

    public static function preview_styles() {
            wp_enqueue_style(
			'eldm-libr-templt',
			self::TEMPLATE_ASSETS . 'css/custom.css',
			null,
			ULTRA_ADDONS_VERSION
		);
    }

	public static function enqueue_assets() {
                
        wp_enqueue_style(
			'eldm-library-editor',
			self::TEMPLATE_ASSETS . 'css/editor.min.css',
			null,
			ULTRA_ADDONS_VERSION
		);

		wp_enqueue_script(
			'eldm-library-editor',
			self::TEMPLATE_ASSETS . 'js/editor.min.js',
			['elementor-editor', 'jquery'],
			ULTRA_ADDONS_VERSION,
			true
		);

            
		wp_enqueue_style(
			'eldm-library-template',
			self::TEMPLATE_ASSETS . 'css/template-library.min.css',
			[
				'elementor-editor',
			],
			ULTRA_ADDONS_VERSION
		);

		wp_enqueue_script(
			'eldm-library-template',
			self::TEMPLATE_ASSETS . 'js/template-library.min.js',
			[
				'eldm-library-editor',
				'jquery-hover-intent',
			],
			ULTRA_ADDONS_VERSION,
			true
		);
        
		
        $localize_data = [
			// 'placeholder_widgets' => Widgets_Manager::proWidgets(),
			// 'hasPro'                  => ultraaddons_is_pro(),
            // 'HELP_ULR'                => ULTRA_ADDONS_WIDGET_HELP_ULR,

			'icon' 						=> self::$theme_demo['button']['icon'],
			'library_title' 			=> self::$theme_demo['library_title'],
			'editor_nonce'            	=> wp_create_nonce( 'eldm_editor_nonce' ),
			'dark_stylesheet_url'     	=> self::TEMPLATE_ASSETS . 'css/editor-dark.min.css',
			'i18n' => [
				'iconDemoTitlePreviewPage'      => self::$theme_demo['button']['text'],//esc_html__( 'Demo', 'ultraaddons' ),
				'promotionDemoDialogHeader'     => esc_html__( '%s Widget', 'ultraaddons' ),
				'promotionDemoDialogMessage'    => esc_html__( 'Use %s widget with other exclusive pro widgets and 100% unique features to extend your toolbox and build sites faster and better.', 'ultraaddons' ),
				'demoEmptyTitle'       => esc_html__( 'No Templates Found', 'ultraaddons' ),
				'demoEmptyMessage'     => esc_html__( 'Try different category or sync for new templates.', 'ultraaddons' ),
				'demoNoResultsTitle'   => esc_html__( 'No Results Found', 'ultraaddons' ),
				'demoNoResultsMessage' => esc_html__( 'Please make sure your search is spelled correctly or try a different words.', 'ultraaddons' ),
			],
		];
        
        wp_localize_script(
			'eldm-library-editor',
			'ELDM_DATA_EDITOR',
			$localize_data
		);
	}

	/**
	 * Undocumented function
	 *
	 * @return Demo_Library_Source
	 */
	public static function get_source() {
		if ( is_null( self::$source ) ) {
			self::$source = new Demo_Library_Source();
		}

		return self::$source;
	}

	public static function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'get_ua_library_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found.', 'ultraaddons' ) );
				}

				ultraaddons_elementor()->db->switch_to_post( $editor_post_id );
			}

			$result = self::get_library_data( $data );

			return $result;
		} );

		$ajax->register_ajax_action( 'get_ua_template_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found', 'ultraaddons' ) );
				}

				ultraaddons_elementor()->db->switch_to_post( $editor_post_id );
			}

			if ( empty( $data['template_id'] ) ) {
				throw new \Exception( __( 'Template id missing', 'ultraaddons' ) );
			}

			$result = self::get_template_data( $data );

			return $result;
		} );
	}

	public static function get_template_data( array $args ) {
		$source = self::get_source();
		$data = $source->get_data( $args );
		return $data;
	}

	/**
	 * Get library data from cache or remote
	 *
	 * type_tags has been added in version 2.15.0
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_library_data( array $args ) {
		$source = self::get_source();

		if ( ! empty( $args['sync'] ) ) {
			Demo_Library_Source::get_library_data( true );
		}

		return [
			'templates' => $source->get_items(),
			'tags'      => $source->get_tags(),
			'type_tags' => $source->get_type_tags(),
		];
	}
}
