<?php
namespace UltraAddons\Traits;

use Elementor\Controls_Manager;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Utils;

trait Group_Control{
    
    
    /**
     * To add Gradient Background Control
     * We will use this Trait
     * and will use method gradient_background()
     * 
     * @param type $selectors
     * @param string $ua_name
     */
    public function gradient_background( $selectors = '', $ua_name = ''){
        if( ! is_string( $selectors ) || ! is_string( $ua_name ) ){
            return;
        }
        //Confirm String name
        if( ! empty( $ua_name ) ){
            $ua_name = 'bacground_' . $this->get_id();
        }
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                        'name' => 'btn_background',
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} ' . $selectors,
                        'fields_options' => [
                                'background' => [
                                        'frontend_available' => true,
                                ],
                                'color' => [
                                        'dynamic' => [],
                                ],
                                'color_b' => [
                                        'dynamic' => [],
                                ],
                        ],
                ]
        );
    }
}
