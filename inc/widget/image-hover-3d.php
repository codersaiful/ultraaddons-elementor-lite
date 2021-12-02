<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
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
        return [ 'ultraaddons', '3d', 'image', 'magic', 'slide'];
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
        //$this->content_general_controls();

    
       
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
        <div>
            
            
        </div>
        <?php
        
    }
    
    
}