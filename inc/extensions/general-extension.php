<?php
namespace UltraAddons\Extensions;


use Elementor\Controls_Manager;
use Elementor\Element_Base;


defined('ABSPATH') || die();

/**
 * General Extension is a Comment Extension
 * is a group of features
 * 
 * FEATURES
 * ************************************
 * 1. Paralax or Scrollax
 * 
 * 
 * CREDITS for Paralax scrollax
 * ************************************
 * @link https://iprodev.github.io/Scrollax.js/
 * @link https://openbase.com/js/scrollax/documentation#download
 * @link https://github.com/iprodev/Scrollax.js
 * 
 * CREDITS for [OtherNameHere]
 * ************************************
 * @link http://url.com
 * 
 * @since 1.1.0.8
 * @author Saiful islam <codersaiful@gmail.com>
 */
class General_Extension{


    /**
     * Initializing Here
     *
     * @return void
     */
    public static function init(){
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );

        //add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );

        

    }

    /**
     * Enqueue Scrollax js min file here
     *
     * @return void
     */
    public static function wp_enqueue_scripts(){

        //Naming of Args for Scrollax
        $name           = 'Scrollax';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/Scrollax/scrollax.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );
    }
    

    /**
     * Adding control for Sticky Section Extension
     *
     * @param Element_Base $element
     * @return void
     */
    public static function add_controls_section( Element_Base $element ){

        //Only for section, this feature will activating
        if ( 'section' !== $element->get_name() ) return;
        
        $tab = Controls_Manager::TAB_LAYOUT;

        $element->start_controls_section( 
            '_ua_general_extension',
            [
                'label' => 'General Extension'. ultraaddons_icon_markup(),
                'description' => __( 'Group of Features. All common feaatures are available in one place.', 'ultraaddons' ),
                'tab' => $tab
            ] 
        );

        $element->add_control(
            '_ua_general_ex_paralax_switch',
            [
                    'label' => __( 'Paralax Background', 'ultraaddons' ),
                    'description' => __( 'Enable Paralax Background for any section to activate Scrollax.', 'ultraaddons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'ultraaddons' ),
                    'label_off' => __( 'Off', 'ultraaddons' ),
                    'return_value' => 'yes',
                    'default' => '',
                    'prefix_class' => 'ua-paralax-',
                    'selectors' => [
                        '{{WRAPPER}}.ua-sticky-yes' => 'position: sticky;width: 100%;',
                    ],
            ]
        );

        $element->end_controls_section();
        
    }
}