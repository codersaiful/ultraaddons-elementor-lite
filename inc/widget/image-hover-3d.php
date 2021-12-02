<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Image_Hover_3D extends Base{
    
    /**
     * Get your widget name
     *
     * @since 1.1.0.8
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons','ua', '3d', 'image', 'magic', 'slide'];
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
        

        //For General Section
        //$this->ua-3dimage-content_general_controls();

    
       
    }
    
    /**
     * Render whole project
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        
        $settings           = $this->get_settings_for_display();

        ?>
        <div class="ua-3d-image-hover-wrapper">
            <div class="ua-3dimage-box">
                <div class="ua-3dimage-imgbx">
                <img src="https://images.unsplash.com/photo-1579748138140-ce9ef2c32db1?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80">
                </div>
                <div class="ua-3dimage-content">
                <div>
                    <h2>Image Title</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.
                    </p>
                </div>
                </div>
            </div>
            <div class="ua-3dimage-box">
                <div class="ua-3dimage-imgbx">
                <img src="https://images.unsplash.com/photo-1579639782539-15cc6c0be63f?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80">
                </div>
                <div class="ua-3dimage-content">
                <div>
                    <h2>Image Title</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.
                    </p>
                </div>
                </div>
            </div>
            <div class="ua-3dimage-box">
                <div class="ua-3dimage-imgbx">
                <img src="https://images.unsplash.com/photo-1603984362497-0a878f607b92?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=700&q=80">
                </div>
                <div class="ua-3dimage-content">
                <div>
                    <h2>Image Title</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.
                    </p>
                </div>
                </div>
            </div>
            <div class="ua-3dimage-box">
                <div class="ua-3dimage-imgbx">
                <img src="https://images.unsplash.com/photo-1579310962131-aa21f240d986?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1234&q=80">
                </div>
                <div class="ua-3dimage-content">
                <div>
                    <h2>Image Title</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi accusamus molestias quidem iusto.
                    </p>
                </div>
                </div>
            </div>
        </div>
        <?php
        
    }
    
    
}