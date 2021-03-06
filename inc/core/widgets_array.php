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
            'icon'      => 'uicon-button',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Advance_Heading' => [
            'name'  => __( 'Advance Heading', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-header',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Info_Box' => [
            'name'  => __( 'Info Box', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-icon-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'List_Item' => [
            'name'  => __( 'Advance List', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-list',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Count_Down_Timer' => [
            'name'  => __( 'Count Down Timer', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-clock',//eicon-countdown
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Slider' => [
            'name'  => __( 'Ultra Slider', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-slider',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Cart' => [
            'name'  => __( 'WC Mini Cart', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-cart',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Menu' => [
            'name'  => __( 'Navigation', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-nav-menu',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Search' => [
            'name'  => __( 'Search Box', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'eicon-nav-menu',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Price_Table' => [
            'name'  => __( 'Pricing Table', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-pricing-table',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Product_Table' => [
            'name'  => __( 'Product Table', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-product-table',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Animated_Header' => [
            'name'  => __( 'Animated Header', 'ultraaddons' ),
            'is_free'   => false,
            'icon'      => 'uicon-animated-header',
            'cat'       => [
                __( 'Modern', 'ultraaddons' ),
                __( 'Pro', 'ultraaddons' ),
            ],
    ],
    
    
    'Product_Slider' => [
            'name'  => __( 'Product Slider', 'ultraaddons' ),
            'is_free'   => false,
            'icon'      => 'uicon-product-slider',
            'cat'       => [
                __( 'WooCommerce', 'ultraaddons' ),
                __( 'Pro', 'ultraaddons' ),
            ],
    ],
    
    
    
    'Contact_Form7' => [
            'name'  => __( 'Contact Form 7', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-contact-form-7',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    'Icon_Box' => [
            'name'  => __( 'Icon Box', 'ultraaddons' ),
            'is_free'   => true,
            'icon'      => 'uicon-icon-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
];

return apply_filters( 'ultraaddons/widgets/array', $widgetsArray );