<?php
namespace UltraAddons\Classes;

use UltraAddons\Extensions\Custom_Fonts as Font;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Custom_Fonts_Render {
   
    public static function init(){
        add_action( 'wp_head', [__CLASS__,'render_style'] );
    }

    public static function render_style(){
        //echo '<h1>hello world</h1>';
        var_dump(3433);
        var_dump(Font::get_fonts());
    }
}
