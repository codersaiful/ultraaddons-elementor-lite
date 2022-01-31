<?php
namespace UltraAddons\Widget;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Simple_Button extends Base{
    use \UltraAddons\Traits\Button_Helper;
    /**
     * Set your widget name keyword
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'button', 'btn', 'bt', 'recent content' ];
    }
    
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        $this->button_register_controls();
        
    }
    
        
    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->button_render();
        
    }
    
}