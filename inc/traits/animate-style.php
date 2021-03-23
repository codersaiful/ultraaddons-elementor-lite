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

trait Animate_Style{
    
    
    /**
     * To add Gradient Background Control
     * We will use this Trait
     * and will use method gradient_background()
     * 
     * @param type $selectors
     * @param string $name
     */
    public function gradient_background( $selectors = '', $name = ''){
        if( ! is_string( $selectors ) || ! is_string( $name ) ){
            return;
        }
        //Confirm String name
        if( ! empty( $name ) ){
            $name = 'bacground_' . $this->get_id();
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
