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
            '' => __( 'None', 'ultraaddons-elementor-lite' ),
            //Attention Seeker
            'animate__bounce' => __( 'bounce', 'ultraaddons-elementor-lite' ),
            'animate__flash' => __( 'flash', 'ultraaddons-elementor-lite' ),
            'animate__pulse' => __( 'pulse', 'ultraaddons-elementor-lite' ),
            'animate__rubberBand' => __( 'rubberBand', 'ultraaddons-elementor-lite' ),
            'animate__shakeX' => __( 'shakeX', 'ultraaddons-elementor-lite' ),
            'animate__shakeY' => __( 'shakeY', 'ultraaddons-elementor-lite' ),
            'animate__headShake' => __( 'headShake', 'ultraaddons-elementor-lite' ),
            'animate__swing' => __( 'swing', 'ultraaddons-elementor-lite' ),
            'animate__tada' => __( 'tada', 'ultraaddons-elementor-lite' ),
            'animate__wobble' => __( 'wobble', 'ultraaddons-elementor-lite' ),
            'animate__jello' => __( 'jello', 'ultraaddons-elementor-lite' ),
            'animate__heartBeat' => __( 'heartBeat', 'ultraaddons-elementor-lite' ),
            //Back Entrances
            'animate__backInDown' => __( 'backInDown', 'ultraaddons-elementor-lite' ),
            'animate__backInLeft' => __( 'backInLeft', 'ultraaddons-elementor-lite' ),
            'animate__backInRight' => __( 'backInRight', 'ultraaddons-elementor-lite' ),
            'animate__backInUp' => __( 'backInUp', 'ultraaddons-elementor-lite' ),
            //Back Exist
            'animate__backOutDown' => __( 'backOutDown', 'ultraaddons-elementor-lite' ),
            'animate__bounceInDown' => __( 'bounceInDown', 'ultraaddons-elementor-lite' ),
            'animate__bounceInLeft' => __( 'bounceInLeft', 'ultraaddons-elementor-lite' ),
            'animate__bounceInRight' => __( 'bounceInRight', 'ultraaddons-elementor-lite' ),
            'animate__bounceInUp' => __( 'bounceInUp', 'ultraaddons-elementor-lite' ),
            //Bouncing Entrances
            'animate__bounceIn' => __( 'bounceIn', 'ultraaddons-elementor-lite' ),
            'animate__bounceInDown' => __( 'bounceInDown', 'ultraaddons-elementor-lite' ),
            'animate__bounceInLeft' => __( 'bounceInLeft', 'ultraaddons-elementor-lite' ),
            'animate__bounceInRight' => __( 'bounceInRight', 'ultraaddons-elementor-lite' ),
            'animate__bounceInUp' => __( 'bounceInUp', 'ultraaddons-elementor-lite' ),
            //Bouncing Exist
            'animate__bounceOut' => __( 'bounceOut', 'ultraaddons-elementor-lite' ),
            'animate__bounceOutDown' => __( 'bounceOutDown', 'ultraaddons-elementor-lite' ),
            'animate__bounceOutLeft' => __( 'bounceOutLeft', 'ultraaddons-elementor-lite' ),
            'animate__bounceOutRight' => __( 'bounceOutRight', 'ultraaddons-elementor-lite' ),
            'animate__bounceOutUp' => __( 'bounceOutUp', 'ultraaddons-elementor-lite' ),
            //Fading Entrances
            'animate__fadeIn' => __( 'fadeIn', 'ultraaddons-elementor-lite' ),
            'animate__fadeInDown' => __( 'fadeInDown', 'ultraaddons-elementor-lite' ),
            'animate__fadeInDownBig' => __( 'fadeInDownBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeInLeft' => __( 'fadeInLeft', 'ultraaddons-elementor-lite' ),
            'animate__fadeInLeftBig' => __( 'fadeInLeftBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeInRight' => __( 'fadeInRight', 'ultraaddons-elementor-lite' ),
            'animate__fadeInRightBig' => __( 'fadeInRightBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeInUp' => __( 'fadeInUp', 'ultraaddons-elementor-lite' ),
            'animate__fadeInUpBig' => __( 'fadeInUpBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeInTopLeft' => __( 'fadeInTopLeft', 'ultraaddons-elementor-lite' ),
            'animate__fadeInTopRight' => __( 'fadeInTopRight', 'ultraaddons-elementor-lite' ),
            'animate__fadeInBottomLeft' => __( 'fadeInBottomLeft', 'ultraaddons-elementor-lite' ),
            'animate__fadeInBottomRight' => __( 'fadeInBottomRight', 'ultraaddons-elementor-lite' ),
            //Fading Exist
            'animate__fadeOut' => __( 'fadeOut', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutDown' => __( 'fadeOutDown', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutDownBig' => __( 'fadeOutDownBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutLeft' => __( 'fadeOutLeft', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutLeftBig' => __( 'fadeOutLeftBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutRight' => __( 'fadeOutRight', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutRightBig' => __( 'fadeOutRightBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutUp' => __( 'fadeOutUp', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutUpBig' => __( 'fadeOutUpBig', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutTopLeft' => __( 'fadeOutTopLeft', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutTopRight' => __( 'fadeOutTopRight', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutBottomLeft' => __( 'fadeOutBottomLeft', 'ultraaddons-elementor-lite' ),
            'animate__fadeOutBottomRight' => __( 'fadeOutBottomRight', 'ultraaddons-elementor-lite' ),

            // Flippers
            'animate__flip' => __( 'flip', 'ultraaddons-elementor-lite' ),
            'animate__flipInX' => __( 'flipInX', 'ultraaddons-elementor-lite' ),
            'animate__flipInY' => __( 'flipInY', 'ultraaddons-elementor-lite' ),
            'animate__flipOutX' => __( 'flipOutX', 'ultraaddons-elementor-lite' ),

            // Lightspeed
            'animate__lightSpeedInRight' => __( 'lightSpeedInRight', 'ultraaddons-elementor-lite' ),
            'animate__lightSpeedInLeft' => __( 'lightSpeedInLeft', 'ultraaddons-elementor-lite' ),
            'animate__lightSpeedOutRight' => __( 'lightSpeedOutRight', 'ultraaddons-elementor-lite' ),
            'animate__lightSpeedOutLeft' => __( 'lightSpeedOutLeft', 'ultraaddons-elementor-lite' ),

            // Rotating entrances
            'animate__rotateIn' => __( 'rotateIn', 'ultraaddons-elementor-lite' ),
            'animate__rotateInDownLeft' => __( 'rotateInDownLeft', 'ultraaddons-elementor-lite' ),
            'animate__rotateInDownRight' => __( 'rotateInDownRight', 'ultraaddons-elementor-lite' ),
            'animate__rotateInUpLeft' => __( 'rotateInUpLeft', 'ultraaddons-elementor-lite' ),
            'animate__rotateInUpRight' => __( 'rotateInUpRight', 'ultraaddons-elementor-lite' ),

            // Rotating exits
            'animate__rotateOut' => __( 'rotateOut', 'ultraaddons-elementor-lite' ),
            'animate__rotateOutDownLeft' => __( 'rotateOutDownLeft', 'ultraaddons-elementor-lite' ),
            'animate__rotateOutDownRight' => __( 'rotateOutDownRight', 'ultraaddons-elementor-lite' ),
            'animate__rotateOutUpLeft' => __( 'rotateOutUpLeft', 'ultraaddons-elementor-lite' ),
            'animate__rotateOutUpRight' => __( 'rotateOutUpRight', 'ultraaddons-elementor-lite' ),

            // Specials
            'animate__hinge' => __( 'hinge', 'ultraaddons-elementor-lite' ),
            'animate__jackInTheBox' => __( 'jackInTheBox', 'ultraaddons-elementor-lite' ),
            'animate__rollIn' => __( 'rollIn', 'ultraaddons-elementor-lite' ),
            'animate__rollOut' => __( 'rollOut', 'ultraaddons-elementor-lite' ),

            // Zooming entrances
            'animate__zoomIn' => __( 'zoomIn', 'ultraaddons-elementor-lite' ),
            'animate__zoomInDown' => __( 'zoomInDown', 'ultraaddons-elementor-lite' ),
            'animate__zoomInLeft' => __( 'zoomInLeft', 'ultraaddons-elementor-lite' ),
            'animate__zoomInRight' => __( 'zoomInRight', 'ultraaddons-elementor-lite' ),
            'animate__zoomInUp' => __( 'zoomInUp', 'ultraaddons-elementor-lite' ),

            // Zooming exits
            'animate__zoomOut' => __( 'zoomOut', 'ultraaddons-elementor-lite' ),
            'animate__zoomOutDown' => __( 'zoomOutDown', 'ultraaddons-elementor-lite' ),
            'animate__zoomOutLeft' => __( 'zoomOutLeft', 'ultraaddons-elementor-lite' ),
            'animate__zoomOutRight' => __( 'zoomOutRight', 'ultraaddons-elementor-lite' ),
            'animate__zoomOutUp' => __( 'zoomOutUp', 'ultraaddons-elementor-lite' ),

            // Sliding entrances
            'animate__slideInDown' => __( 'slideInDown', 'ultraaddons-elementor-lite' ),
            'animate__slideInLeft' => __( 'slideInLeft', 'ultraaddons-elementor-lite' ),
            'animate__slideInRight' => __( 'slideInRight', 'ultraaddons-elementor-lite' ),
            'animate__slideInUp' => __( 'slideInUp', 'ultraaddons-elementor-lite' ),

            // Sliding exits
            'animate__slideOutDown' => __( 'slideOutDown', 'ultraaddons-elementor-lite' ),
            'animate__slideOutLeft' => __( 'slideOutLeft', 'ultraaddons-elementor-lite' ),
            'animate__slideOutRight' => __( 'slideOutRight', 'ultraaddons-elementor-lite' ),
            'animate__slideOutRight' => __( 'slideOutRight', 'ultraaddons-elementor-lite' ),

        ];

        
        return apply_filters( 'ultraaddons/animate_style/list', $animation_options );

    }
}
