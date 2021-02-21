<?php
/**
 * List of Widget.
 * 
 * All of Supported widget will add here as array.
 * 
 * 
 * @author Saiful
 */


$widgetsArray = [
    
    'Button'=> [
            'name'      => __( 'Button', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-button',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Advance_Heading' => [
            'name'  => __( 'Advance Heading', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-heading',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Info_Box' => [
            'name'  => __( 'Info Box', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-icon-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'List_Item' => [
            'name'  => __( 'Advance List', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-editor-list-ul',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Count_Down_Timer' => [
            'name'  => __( 'Count Down Timer', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-eicon-countdown',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Slider' => [
            'name'  => __( 'Ultra Slider', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-slider-device',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Cart' => [
            'name'  => __( 'WC Mini Cart', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-cart-light',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'WOO_Product_Table' => [
            'name'  => __( 'Product Table', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-table',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
];

return apply_filters( 'ultraaddons/widgets/array', $widgetsArray );