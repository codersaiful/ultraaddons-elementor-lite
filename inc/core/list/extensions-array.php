<?php

use Elementor\Controls_Manager;
/**
 * List of Extension.
 * 
 * All of Supported widget will add here as array.
 * 
 * 
 * @author Saiful
 */


$extensionsArray = [
            'Wrapper_Link'=> [
                    'name'      => __( 'Wrapper Link', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => false,
                    'icon'      => 'uicon-button',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
            ],


            'Hover_Effect' => [
                    'name'  => __( 'Hover Effect', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => true,
                    'icon'      => 'uicon-hover',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
            ],
            
            /**
             * CSS Transform Editing for
             * Any content/any element/
             * We will added this Extenstion at V1.0.3.0
             * 
             * @date 4.3.2021
             * @since 1.0.3.0
             */
            'Transform' => [
                    'name'  => __( 'CSS Transform', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => true,
                    'icon'      => 'eicon-heading',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
                    'tab' => Controls_Manager::TAB_STYLE, //This is only for PlaceHolder Extension. showing message on free user
            ],
            

            'Animation_Effect' => [
                    'name'  => __( 'Animation Effect', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => true,
                    'icon'      => 'eicon-code-highlight',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
                    'tab' => Controls_Manager::TAB_STYLE,  //This is only for PlaceHolder Extension. showing message on free user
            ],

            'Background_Overlay' => [
                    'name'  => __( 'Background Overlay', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => false,
                    'icon'      => 'eicon-background',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
            ],

//            'Ultra_Effect' => [
//                    'name'  => __( 'Ultra Effect', 'ultraaddons-elementor-lite' ),
//                    'is_pro'   => true,
//                    'icon'      => 'eicon-spinner',
//                    'cat'       => [
//                        __( 'Basic', 'ultraaddons-elementor-lite' ),
//                    ],
//            ],

            'Gradient_Text' => [
                    'name'  => __( 'Gradient Text', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => true,
                    'icon'      => 'eicon-global-colors',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
            ],
//              //Preset currently disabled. unable to solved. @Saiful
//            'Preset' => [
//                    'name'  => __( 'Preset Settings', 'ultraaddons-elementor-lite' ),
//                    'is_pro'   => true,
//                    'icon'      => 'eicon-click',
//                    'cat'       => [
//                        __( 'Basic', 'ultraaddons-elementor-lite' ),
//                    ],
//            ],
            
            'Floating_Effects' => [
                    'name'  => __( 'Floating Effects', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => false,
                    'icon'      => 'eicon-click',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
            ],
             'Custom_CSS' => [
                     'name'  => __( 'Custom CSS', 'ultraaddons-elementor-lite' ),
                     'is_pro'   => false,
                     'icon'      => 'eicon-click',
                     'cat'       => [
                         __( 'Basic', 'ultraaddons-elementor-lite' ),
                     ],
             ],
             
            /**
             * Currently Added Custom font 
             * 
             * @author Saiful Islam<codersaiful@gmail.com>
             * 
             * @since 1.1.0.1
             */
            'Custom_Fonts' => [
                    'name'  => __( 'Custom Fonts', 'ultraaddons-elementor-lite' ),
                    'is_pro'   => false,
                    'icon'      => 'eicon-font',
                    'cat'       => [
                        __( 'Basic', 'ultraaddons-elementor-lite' ),
                    ],
            ],


            'Sticky_Section' => [
                'name'  => __( 'Sticky Section on Scroll', 'ultraaddons-elementor-lite' ),
                'is_pro'   => false,
                'icon'      => 'eicon-scroll',
                'cat'       => [
                    __( 'Basic', 'ultraaddons-elementor-lite' ),
                ],
            ],

            /**
             * General Extension is a Comment Extension
             * is a group of features
             * 
             * @since 1.1.0.8
             * @author Saiful islam <codersaiful@gmail.com>
             */
            'General_Extension' => [
                'name'  => __( 'General Extension', 'ultraaddons-elementor-lite' ),
                'is_pro'   => true,
                'icon'      => 'uicon-ultraaddons',
                'cat'       => [
                    __( 'Basic', 'ultraaddons-elementor-lite' ),
                ],
            ],


            /**
             * Target is that for this extension:
             * User will be able to set condition for any content or section. 
             */
            'Conditional_Content' => [
                'name'  => __( 'Conditional Content', 'ultraaddons-elementor-lite' ),
                'is_pro'   => true,
                'icon'      => 'uicon-ultraaddons',
                'cat'       => [
                    __( 'Basic', 'ultraaddons-elementor-lite' ),
                ],
                'tab' => Controls_Manager::TAB_ADVANCED,
            ],


            
        ];

return apply_filters( 'ultraaddons/extensions/array', $extensionsArray );
