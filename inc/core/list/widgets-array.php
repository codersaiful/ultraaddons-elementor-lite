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
            'is_pro'   => false,
            'icon'      => 'uicon-button',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Advance_Heading' => [
            'name'  => __( 'Advance Heading', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-header',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Info_Box' => [
            'name'  => __( 'Info Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-icon-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'List_Item' => [
            'name'  => __( 'Advance List', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-list',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Count_Down_Timer' => [
            'name'  => __( 'Count Down Timer', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-clock',//eicon-countdown
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Slider' => [
            'name'  => __( 'Ultra Slider', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-slider',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Cart' => [
            'name'  => __( 'WC Mini Cart', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-cart',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Menu' => [
            'name'  => __( 'Navigation', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-nav-menu',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Search' => [
            'name'  => __( 'Search Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-nav-menu',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Price_Table' => [
            'name'  => __( 'Pricing Table', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-pricing-table',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Product_Table' => [
            'name'  => __( 'Product Table', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-product-table',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Animated_Header' => [
            'name'  => __( 'Animated Header', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'uicon-animated-header',
            'cat'       => [
                __( 'Modern', 'ultraaddons' ),
                __( 'Pro', 'ultraaddons' ),
            ],
    ],
    
    
    'Product_Slider' => [
            'name'  => __( 'Product Slider', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'uicon-product-slider',
            'cat'       => [
                __( 'WooCommerce', 'ultraaddons' ),
                __( 'Pro', 'ultraaddons' ),
            ],
    ],
    
    
    //New Added
    'Hero_Banner' => [
            'name'  => __( 'Hero Banner', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-call-to-action',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Contact_Form7' => [
            'name'  => __( 'Contact Form 7', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-contact-form-7',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    'Icon_Box' => [
            'name'  => __( 'Icon Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-icon-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'WC_Products' => [
            'name'  => __( 'WooCommerce Products', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-product',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Testimonial_Slider' => [
            'name'  => __( 'Testimonial Slider', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-testimonial-carousel',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Testimonial_Box' => [
            'name'  => __( 'Testimonial Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-testimonial',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'Team_Box' => [
            'name'  => __( 'Team Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-team-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'WC_Categories' => [
            'name'  => __( 'Product Categories', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'uicon-icon-box',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    //New Added Widgets
    'Counter' => [
            'name'  => __( 'Counter Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-counter',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Gallery_Box' => [
            'name'  => __( 'Gallery Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-gallery-justified',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Info_Boards' => [
            'name'  => __( 'Info Boards', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-form-horizontal',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Offer_Card' => [
            'name'  => __( 'Offer Card', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-form-horizontal',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Recent_Blog' => [
            'name'  => __( 'Recent Blog', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-blog-list',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Skill_Bar' => [
            'name'  => __( 'Skill Bar', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-skillbar',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    
    'WordPress_Plugin_Stats' => [
            'name'  => __( 'WordPress Plugin Stats', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-skillbar',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Post_Title' => [
            'name'  => __( 'Post Title / Page Title', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'uicon-skillbar',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Breadcrumb' => [
            'name'  => __( 'Breadcrumb', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'uicon-skillbar',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Alert' => [
            'name'  => __( 'Alert', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-alert',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Timeline' => [
            'name'  => __( 'Timeline', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-time-line',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Image_Accordion' => [
            'name'  => __( 'Image Accordion', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-accordion',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Product_Accordion' => [
            'name'  => __( 'Product Accordion', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-accordion',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Accordion' => [
            'name'  => __( 'Accordion', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-accordion',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Drop_Caps' => [
            'name'  => __( 'Drop Caps', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-accordion',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Post_Masonry' => [
            'name'  => __( 'Advance Post Masonry', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-posts-masonry',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Portfolio' => [
            'name'  => __( 'Portfolio', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-posts-masonry',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],

    'Animated_Headline' => [
            'name'  => __( 'Animated Headline', 'ultraaddons' ),
            'is_pro'   => true,
            'icon'      => 'eicon-posts-masonry',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],
    
    'Flip_Box_3d' => [
            'name'  => __( '3d Flip Box', 'ultraaddons' ),
            'is_pro'   => false,
            'icon'      => 'eicon-posts-masonry',
            'cat'       => [
                __( 'Basic', 'ultraaddons' ),
            ],
    ],

    
];

return apply_filters( 'ultraaddons/widgets/array', $widgetsArray );
