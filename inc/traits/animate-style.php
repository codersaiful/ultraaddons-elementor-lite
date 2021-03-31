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
        $animation_options =  [
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

            // Flippers
            'animate__flip' => __( 'flip', 'ultraaddons' ),
            'animate__flipInX' => __( 'flipInX', 'ultraaddons' ),
            'animate__flipInY' => __( 'flipInY', 'ultraaddons' ),
            'animate__flipOutX' => __( 'flipOutX', 'ultraaddons' ),

            // Lightspeed
            'animate__lightSpeedInRight' => __( 'lightSpeedInRight', 'ultraaddons' ),
            'animate__lightSpeedInLeft' => __( 'lightSpeedInLeft', 'ultraaddons' ),
            'animate__lightSpeedOutRight' => __( 'lightSpeedOutRight', 'ultraaddons' ),
            'animate__lightSpeedOutLeft' => __( 'lightSpeedOutLeft', 'ultraaddons' ),

            // Rotating entrances
            'animate__rotateIn' => __( 'rotateIn', 'ultraaddons' ),
            'animate__rotateInDownLeft' => __( 'rotateInDownLeft', 'ultraaddons' ),
            'animate__rotateInDownRight' => __( 'rotateInDownRight', 'ultraaddons' ),
            'animate__rotateInUpLeft' => __( 'rotateInUpLeft', 'ultraaddons' ),
            'animate__rotateInUpRight' => __( 'rotateInUpRight', 'ultraaddons' ),

            // Rotating exits
            'animate__rotateOut' => __( 'rotateOut', 'ultraaddons' ),
            'animate__rotateOutDownLeft' => __( 'rotateOutDownLeft', 'ultraaddons' ),
            'animate__rotateOutDownRight' => __( 'rotateOutDownRight', 'ultraaddons' ),
            'animate__rotateOutUpLeft' => __( 'rotateOutUpLeft', 'ultraaddons' ),
            'animate__rotateOutUpRight' => __( 'rotateOutUpRight', 'ultraaddons' ),

            // Specials
            'animate__hinge' => __( 'hinge', 'ultraaddons' ),
            'animate__jackInTheBox' => __( 'jackInTheBox', 'ultraaddons' ),
            'animate__rollIn' => __( 'rollIn', 'ultraaddons' ),
            'animate__rollOut' => __( 'rollOut', 'ultraaddons' ),

            // Zooming entrances
            'animate__zoomIn' => __( 'zoomIn', 'ultraaddons' ),
            'animate__zoomInDown' => __( 'zoomInDown', 'ultraaddons' ),
            'animate__zoomInLeft' => __( 'zoomInLeft', 'ultraaddons' ),
            'animate__zoomInRight' => __( 'zoomInRight', 'ultraaddons' ),
            'animate__zoomInUp' => __( 'zoomInUp', 'ultraaddons' ),

            // Zooming exits
            'animate__zoomOut' => __( 'zoomOut', 'ultraaddons' ),
            'animate__zoomOutDown' => __( 'zoomOutDown', 'ultraaddons' ),
            'animate__zoomOutLeft' => __( 'zoomOutLeft', 'ultraaddons' ),
            'animate__zoomOutRight' => __( 'zoomOutRight', 'ultraaddons' ),
            'animate__zoomOutUp' => __( 'zoomOutUp', 'ultraaddons' ),

            // Sliding entrances
            'animate__slideInDown' => __( 'slideInDown', 'ultraaddons' ),
            'animate__slideInLeft' => __( 'slideInLeft', 'ultraaddons' ),
            'animate__slideInRight' => __( 'slideInRight', 'ultraaddons' ),
            'animate__slideInUp' => __( 'slideInUp', 'ultraaddons' ),

            // Sliding exits
            'animate__slideOutDown' => __( 'slideOutDown', 'ultraaddons' ),
            'animate__slideOutLeft' => __( 'slideOutLeft', 'ultraaddons' ),
            'animate__slideOutRight' => __( 'slideOutRight', 'ultraaddons' ),
            'animate__slideOutRight' => __( 'slideOutRight', 'ultraaddons' ),

        ];

        
        return apply_filters( 'ultraaddons/animate_style/list', $animation_options );

    }
}
