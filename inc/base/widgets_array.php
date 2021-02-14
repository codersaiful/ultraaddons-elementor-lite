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
            'name'  => __( 'Button', 'ultraaddons' ),
    ],
    
    'Advance_Heading' => [
            'name'  => __( 'Advance Heading', 'ultraaddons' ),
    ],
    
    'Count_Down_Timer' => [
            'name'  => __( 'Count Down Titme', 'ultraaddons' ),
    ],
    
];

return apply_filters( 'ultraaddons/widgets/array', $widgetsArray );