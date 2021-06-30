<?php
namespace UltraAddons\Library;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

defined('ABSPATH') || die();

class Library_Manager {

	protected static $source = null;
        const ULTRA_ADDONS_TEMPLATE_ASSETS = ULTRA_ADDONS_URL . 'inc/library/assets/';

        public static function init() {
            
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
                update_option( 'ultraaddons_library_cache', false );
                //**************************************************/
                
		add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
		add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
                
                // Enqueue editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
                
                // enqueue modal's preview css.
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'preview_styles' ] );
	}

	public static function print_template_views() {
		include_once __DIR__ . '/templates/template.php';
	}

        public static function preview_styles() {
            wp_enqueue_style(
			'ultraaddons-libr-templt',
			self::ULTRA_ADDONS_TEMPLATE_ASSETS . 'css/custom.css',
			null,
			ULTRA_ADDONS_VERSION
		);
        }
	public static function enqueue_assets() {
                
                wp_enqueue_style(
			'ultraaddons-library-editor',
			self::ULTRA_ADDONS_TEMPLATE_ASSETS . 'css/editor.min.css',
			null,
			ULTRA_ADDONS_VERSION
		);

		wp_enqueue_script(
			'ultraaddons-library-editor',
			self::ULTRA_ADDONS_TEMPLATE_ASSETS . 'js/editor.min.js',
			['elementor-editor', 'jquery'],
			ULTRA_ADDONS_VERSION,
			true
		);

            
		wp_enqueue_style(
			'ultraaddons-library-template',
			self::ULTRA_ADDONS_TEMPLATE_ASSETS . 'css/template-library.min.css',
			[
				'elementor-editor',
			],
			ULTRA_ADDONS_VERSION
		);

		wp_enqueue_script(
			'ultraaddons-library-template',
			self::ULTRA_ADDONS_TEMPLATE_ASSETS . 'js/template-library.min.js',
			[
				'ultraaddons-library-editor',
				'jquery-hover-intent',
			],
			ULTRA_ADDONS_VERSION,
			true
		);
                
                $localize_data = [
			'placeholder_widgets' => [],
			'hasPro'                  => ultraaddons_is_pro(),
			'editor_nonce'            => wp_create_nonce( 'ua_editor_nonce' ),
			'dark_stylesheet_url'     => self::ULTRA_ADDONS_TEMPLATE_ASSETS . 'css/editor-dark.min.css',
			'i18n' => [
				'iconTitlePreviewPage'      => esc_html__( 'Library', 'ultraaddons' ),
				'promotionDialogHeader'     => esc_html__( '%s Widget', 'ultraaddons' ),
				'promotionDialogMessage'    => esc_html__( 'Use %s widget with other exclusive pro widgets and 100% unique features to extend your toolbox and build sites faster and better.', 'ultraaddons' ),
				'templatesEmptyTitle'       => esc_html__( 'No Templates Found', 'ultraaddons' ),
				'templatesEmptyMessage'     => esc_html__( 'Try different category or sync for new templates.', 'ultraaddons' ),
				'templatesNoResultsTitle'   => esc_html__( 'No Results Found', 'ultraaddons' ),
				'templatesNoResultsMessage' => esc_html__( 'Please make sure your search is spelled correctly or try a different words.', 'ultraaddons' ),
			],
		];
                
                wp_localize_script(
			'ultraaddons-library-editor',
			'ULTRAADDONS_DATA_EDITOR',
			$localize_data
		);
	}

	/**
	 * Undocumented function
	 *
	 * @return Library_Source
	 */
	public static function get_source() {
		if ( is_null( self::$source ) ) {
			self::$source = new Library_Source();
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
			Library_Source::get_library_data( true );
		}

		return [
			'templates' => $source->get_items(),
			'tags'      => $source->get_tags(),
			'type_tags' => $source->get_type_tags(),
		];
	}
}
