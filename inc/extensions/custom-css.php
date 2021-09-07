<?php
namespace UltraAddons\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Controls_Stack;
use Elementor\Core\DynamicTags\Dynamic_CSS;
use ElementorPro\Base\Module_Base;
use ElementorPro\Plugin;

defined('ABSPATH') || die();

class Custom_CSS {
    public static function init() {

        add_action( 'elementor/element/after_section_end', [ __CLASS__, 'register_controls' ], 10, 2 );
        add_action( 'elementor/element/parse_css', [ __CLASS__, 'add_post_css' ], 10, 2 );
		add_action( 'elementor/css-file/post/parse', [ __CLASS__, 'add_page_settings_css' ] );

        add_filter( 'elementor_pro/editor/localize_settings', [ __CLASS__, 'localize_settings' ] );
    }

    /**
	 * @param $post_css Post
	 * @param $element  Element_Base
	 */
	public function add_post_css( $post_css, $element ) {
		if ( $post_css instanceof Dynamic_CSS ) {
			return;
		}

		$element_settings = $element->get_settings();

		if ( empty( $element_settings['custom_css'] ) ) {
			return;
		}

		$css = trim( $element_settings['custom_css'] );

		if ( empty( $css ) ) {
			return;
		}
		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		// Add a css comment
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $css );
	}

    /**
	 * @param $post_css Post
	 */
	public function add_page_settings_css( $post_css ) {
		$document = Plugin::elementor()->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'custom_css' );

		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

    /**
	 * @param $element    Controls_Stack
	 * @param $section_id string
	 */
	public static function register_controls( Controls_Stack $element, $section_id ) {
		// Remove Custom CSS Banner (From free version)
		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		$this->add_controls_section( __Class__, $element );
	}

    public function add_controls_section( $element ) {

        $old_section = Plugin::elementor()->controls_manager->get_control_from_stack( $element->get_unique_name(), 'section_custom_css_pro' );

		Plugin::elementor()->controls_manager->remove_control_from_stack( $element->get_unique_name(), [ 'section_custom_css_pro', 'custom_css_pro' ] );


        $element->start_controls_section(
			'section_custom_css',
			[
				'label' => __( 'Custom CSS', 'elementor-pro' ),
				'tab' => $old_section['tab'],
			]
		);

		$element->add_control(
			'custom_css_title',
			[
				'raw' => __( 'Add your own custom CSS here', 'elementor-pro' ),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$element->add_control(
			'custom_css',
			[
				'type' => Controls_Manager::CODE,
				'label' => __( 'Custom CSS', 'elementor-pro' ),
				'language' => 'css',
				'render_type' => 'ui',
				'show_label' => false,
				'separator' => 'none',
			]
		);

		$element->add_control(
			'custom_css_description',
			[
				'raw' => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'elementor-pro' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$element->end_controls_section();

    }

    public function localize_settings( array $settings ) {
		$settings['i18n']['custom_css'] = __( 'Custom CSS', 'elementor-pro' );

		return $settings;
	}

}