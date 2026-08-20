<?php
/**
 * Library api class
 *
 * @package UltraAddons
 * @author Saiful Islam<codersaiful@gmail.com>
 */
namespace UltraAddons\Library;

use Elementor\TemplateLibrary\Source_Base;

defined( 'ABSPATH' ) || die();

class Library_Source extends Source_Base {

	/**
	 * Template library data cache
	 */
	const LIBRARY_CACHE_KEY = 'ultraaddons_library_cache';

	/**
	 * Template info api url
	 *
	 * Updated api to v2 in version 2.15.0
         * 
         * base site: https://library.ultraaddons.com/
         * 
	 */
        const API_TEMPLATES_INFO_URL = 'https://library.ultraaddons.com/wp-json/library/v2/templates';

        /**
         * Get Sinle Template info
         * 
         * Example Link:
         * https://library.ultraaddons.com/wp-json/library/v2/template/[template_id]
         */
	const API_TEMPLATE_DATA_URL = 'https://library.ultraaddons.com/wp-json/library/v2/template/';

	public function get_id() {
		return 'ultraaddons-library';
	}

	public function get_title() {
		return __( 'UltraAddons Library', 'ultraaddons-elementor-lite' );
	}

	public function register_data() {}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a UltraAddons library' );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a UltraAddons library' );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a UltraAddons library' );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a UltraAddons library' );
	}

	public function get_items( $ultraaddons_args = [] ) {
		$library_data = self::get_library_data();

		$ultraaddons_templates = [];

		if ( ! empty( $library_data['templates'] ) ) {
			foreach ( $library_data['templates'] as $template_data ) {
				$ultraaddons_templates[] = $this->prepare_template( $template_data );
			}
		}

		return $ultraaddons_templates;
	}

	public function get_tags() {
		$library_data = self::get_library_data();

		return ( ! empty( $library_data['tags'] ) ? $library_data['tags'] : [] );
	}

	public function get_type_tags() {
		$library_data = self::get_library_data();

		return ( ! empty( $library_data['type_tags'] ) ? $library_data['type_tags'] : [] );
	}

	/**
	 * Prepare template items to match model
	 *
	 * @param array $template_data
	 * @return array
	 */
	private function prepare_template( array $template_data ) {
		return [
			'template_id' => $template_data['id'],
			'title'       => $template_data['title'],
			'type'        => $template_data['type'],
			'thumbnail'   => $template_data['thumbnail'],
			'date'        => $template_data['created_at'],
			'tags'        => $template_data['tags'],
			'isPro'       => $template_data['is_pro'],
			'url'         => $template_data['url'],
			'extra'       => $template_data['extra'],
		];
	}

	/**
	 * Get library data from remote source and cache
	 *
	 * @param boolean $force_update
	 * @return array
	 */
	private static function request_library_data( $force_update = false ) {
		$data = get_option( self::LIBRARY_CACHE_KEY );

		if ( $force_update || false === $data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get( self::API_TEMPLATES_INFO_URL, [
				'timeout' => $timeout,
			] );

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				update_option( self::LIBRARY_CACHE_KEY, [] );
				return false;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $data ) || ! is_array( $data ) ) {
				update_option( self::LIBRARY_CACHE_KEY, [] );
				return false;
			}

			update_option( self::LIBRARY_CACHE_KEY, $data, 'no' );
		}

		return $data;
	}

	/**
	 * Get library data
	 *
	 * @param boolean $force_update
	 * @return array
	 */
	public static function get_library_data( $force_update = false ) {
		self::request_library_data( $force_update );

		$data = get_option( self::LIBRARY_CACHE_KEY );

		if ( empty( $data ) ) {
			return [];
		}

		return $data;
	}

	/**
	 * Get remote template.
	 *
	 * Retrieve a single remote template from UltraAddons.com servers.
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return array Remote template.
	 */
	public function get_item( $template_id ) {
		$ultraaddons_templates = $this->get_items();

		foreach ( $ultraaddons_templates as $template ) {
			if ( isset( $template['template_id'] ) && (string) $template['template_id'] === (string) $template_id ) {
				return $template;
			}
		}

		return [];
	}

	public static function request_template_data( $template_id ) {
		$template_id = absint( $template_id );
		if ( ! $template_id ) {
			return new \WP_Error( 'invalid_template_id', esc_html__( 'Invalid template ID.', 'ultraaddons-elementor-lite' ) );
		}

		$body = [
			'home_url' => trailingslashit( home_url() ),
			'version' => ULTRA_ADDONS_VERSION,
		];

		if ( ultraaddons_is_pro() ) {
			$body['has_pro'] = 1;
			$body['pro_version'] = ULTRA_ADDONS_PRO_VERSION;
		}

		$response = wp_remote_get(
			self::API_TEMPLATE_DATA_URL . $template_id,
			[
				'body' => $body,
				'timeout' => 25
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return new \WP_Error( 'template_request_failed', esc_html__( 'Unable to download the template.', 'ultraaddons-elementor-lite' ) );
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from Elementor.com servers.
	 *
	 * @return array|\WP_Error Remote Template data.
	 */
	public function get_data( array $ultraaddons_args, $context = 'display' ) {
		$template_id = isset( $ultraaddons_args['template_id'] ) ? absint( $ultraaddons_args['template_id'] ) : 0;
		$data = self::request_template_data( $template_id );

		if ( is_wp_error( $data ) ) {
			throw new \Exception( esc_html( $data->get_error_message() ) );
		}

		$data = json_decode( $data, true );

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new \Exception( esc_html__( 'Template does not have any content', 'ultraaddons-elementor-lite' ) );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id = isset( $ultraaddons_args['editor_post_id'] ) ? absint( $ultraaddons_args['editor_post_id'] ) : 0;
		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			throw new \Exception( esc_html__( 'You are not allowed to edit this post.', 'ultraaddons-elementor-lite' ) );
		}
		$document = ultraaddons_elementor()->documents->get( $post_id );

		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}
}
