<?php
namespace UltraAddons\Controls;

use Elementor\Base_Data_Control;

defined( 'ABSPATH' ) || die();

/**
 * UltraAddons Select2 Control
 *
 * Provides an AJAX-powered Select2 control in Elementor Editor
 * requiring a minimum of 3 characters to search and select products.
 *
 * @since 2.0.4.0
 * @package UltraAddons\Controls
 */
class Select2 extends Base_Data_Control {

    /**
     * Retrieve control type
     *
     * @return string
     */
    public function get_type() {
        return 'ua-select2';
    }

    /**
     * Enqueue control scripts and styles
     *
     * @return void
     */
    public function enqueue() {
        $deps = wp_script_is( 'jquery-elementor-select2', 'registered' )
            ? [ 'jquery', 'jquery-elementor-select2' ]
            : [ 'jquery' ];

        wp_register_script(
            'ua-select2-control',
            ULTRA_ADDONS_ASSETS . 'js/ua-select2.js',
            $deps,
            ULTRA_ADDONS_VERSION,
            true
        );

        wp_localize_script(
            'ua-select2-control',
            'ua_select2_localize',
            [
                'ajaxurl'     => admin_url( 'admin-ajax.php' ),
                'search_text' => esc_html__( 'Search products...', 'ultraaddons-elementor-lite' ),
                'nonce'       => wp_create_nonce( 'ua_select2_nonce' ),
            ]
        );

        wp_enqueue_script( 'ua-select2-control' );
    }

    /**
     * Default control settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return [
            'multiple'    => false,
            'source_name' => 'post_type',
            'source_type' => 'product',
        ];
    }

    /**
     * Render control template in Elementor editor
     *
     * @return void
     */
    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="ua-select2" data-setting="{{ data.name }}"></select>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <#
        ( function( $ ) {
            $( document.body ).trigger( 'ua_select2_init', {
                currentID: data.controlValue,
                data: data,
                controlUID: controlUID,
                multiple: data.multiple
            } );
        }( jQuery ) );
        #>
        <?php
    }
}
