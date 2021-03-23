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

/**
 * Controlling Animate Style as Trait
 * 
 * If need any where animate.style name, we able to handle it.
 */
trait Animate_Style{
    
    /**
     * Get Animation name as Array from animate.css
     * 
     * @link https://animate.style Taking name from Animate Style
     * @return type
     */
    public function get_animations(){
                return [
                    '' => __( 'None', 'ultraaddons' ),
                    //Attention Seeker
                    'animate__bounce' => __( 'bounce', 'ultraaddons' ),
                    'animate__flash' => __( 'flash', 'ultraaddons' ),
                    'animate__pulse' => __( 'pulse', 'ultraaddons' ),
                    'animate__rubberBand' => __( 'rubberBand', 'ultraaddons' ),
                    'animate__shakeX' => __( 'shakeX', 'ultraaddons' ),
                    'animate__shakeY' => __( 'shakeY', 'ultraaddons' ),
                    'animate__headShake' => __( 'headShake', 'ultraaddons' ),
                    'animate__swing' => __( 'swing', 'ultraaddons' ),
                    'animate__tada' => __( 'tada', 'ultraaddons' ),
                    'animate__wobble' => __( 'wobble', 'ultraaddons' ),
                    'animate__jello' => __( 'jello', 'ultraaddons' ),
                    'animate__heartBeat' => __( 'heartBeat', 'ultraaddons' ),
                    //Back Entrances
                    'animate__backInDown' => __( 'backInDown', 'ultraaddons' ),
                    'animate__backInLeft' => __( 'backInLeft', 'ultraaddons' ),
                    'animate__backInRight' => __( 'backInRight', 'ultraaddons' ),
                    'animate__backInUp' => __( 'backInUp', 'ultraaddons' ),
                    //Back Exist
                    'animate__backOutDown' => __( 'backOutDown', 'ultraaddons' ),
                    'animate__bounceInDown' => __( 'bounceInDown', 'ultraaddons' ),
                    'animate__bounceInLeft' => __( 'bounceInLeft', 'ultraaddons' ),
                    'animate__bounceInRight' => __( 'bounceInRight', 'ultraaddons' ),
                    'animate__bounceInUp' => __( 'bounceInUp', 'ultraaddons' ),
                    //Bouncing Entrances
                    'animate__bounceIn' => __( 'bounceIn', 'ultraaddons' ),
                    'animate__bounceInDown' => __( 'bounceInDown', 'ultraaddons' ),
                    'animate__bounceInLeft' => __( 'bounceInLeft', 'ultraaddons' ),
                    'animate__bounceInRight' => __( 'bounceInRight', 'ultraaddons' ),
                    'animate__bounceInUp' => __( 'bounceInUp', 'ultraaddons' ),
                    //Bouncing Exist
                    'animate__bounceOut' => __( 'bounceOut', 'ultraaddons' ),
                    'animate__bounceOutDown' => __( 'bounceOutDown', 'ultraaddons' ),
                    'animate__bounceOutLeft' => __( 'bounceOutLeft', 'ultraaddons' ),
                    'animate__bounceOutRight' => __( 'bounceOutRight', 'ultraaddons' ),
                    'animate__bounceOutUp' => __( 'bounceOutUp', 'ultraaddons' ),
                    //Fading Entrances
                    'animate__fadeIn' => __( 'fadeIn', 'ultraaddons' ),
                    'animate__fadeInDown' => __( 'fadeInDown', 'ultraaddons' ),
                    'animate__fadeInDownBig' => __( 'fadeInDownBig', 'ultraaddons' ),
                    'animate__fadeInLeft' => __( 'fadeInLeft', 'ultraaddons' ),
                    'animate__fadeInLeftBig' => __( 'fadeInLeftBig', 'ultraaddons' ),
                    'animate__fadeInRight' => __( 'fadeInRight', 'ultraaddons' ),
                    'animate__fadeInRightBig' => __( 'fadeInRightBig', 'ultraaddons' ),
                    'animate__fadeInUp' => __( 'fadeInUp', 'ultraaddons' ),
                    'animate__fadeInUpBig' => __( 'fadeInUpBig', 'ultraaddons' ),
                    'animate__fadeInTopLeft' => __( 'fadeInTopLeft', 'ultraaddons' ),
                    'animate__fadeInTopRight' => __( 'fadeInTopRight', 'ultraaddons' ),
                    'animate__fadeInBottomLeft' => __( 'fadeInBottomLeft', 'ultraaddons' ),
                    'animate__fadeInBottomRight' => __( 'fadeInBottomRight', 'ultraaddons' ),
                    //Fading Exist
                    'animate__fadeOut' => __( 'fadeOut', 'ultraaddons' ),
                    'animate__fadeOutDown' => __( 'fadeOutDown', 'ultraaddons' ),
                    'animate__fadeOutDownBig' => __( 'fadeOutDownBig', 'ultraaddons' ),
                    'animate__fadeOutLeft' => __( 'fadeOutLeft', 'ultraaddons' ),
                    'animate__fadeOutLeftBig' => __( 'fadeOutLeftBig', 'ultraaddons' ),
                    'animate__fadeOutRight' => __( 'fadeOutRight', 'ultraaddons' ),
                    'animate__fadeOutRightBig' => __( 'fadeOutRightBig', 'ultraaddons' ),
                    'animate__fadeOutUp' => __( 'fadeOutUp', 'ultraaddons' ),
                    'animate__fadeOutUpBig' => __( 'fadeOutUpBig', 'ultraaddons' ),
                    'animate__fadeOutTopLeft' => __( 'fadeOutTopLeft', 'ultraaddons' ),
                    'animate__fadeOutTopRight' => __( 'fadeOutTopRight', 'ultraaddons' ),
                    'animate__fadeOutBottomLeft' => __( 'fadeOutBottomLeft', 'ultraaddons' ),
                    'animate__fadeOutBottomRight' => __( 'fadeOutBottomRight', 'ultraaddons' ),
                    
                    //Flippers
                    'sssssssss' => __( 'sssssssss', 'ultraaddons' ),
                    'sssssssss' => __( 'sssssssss', 'ultraaddons' ),

                ];
    }
}
