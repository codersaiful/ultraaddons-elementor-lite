<?php
namespace UltraAddons\Widget;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;


class Icon_Box extends Info_Box {
    
    public function get_keywords() {
            return [ 'ultraaddons','ua', 'icon', 'icon box', 'box' ];
    }
    
    
    protected function register_controls() {
        parent::_register_controls();
        
    }
    
}
