<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();
/**
 * Description of Header_Footer
 *
 * @author CODES
 */
class Header_Footer {
    
    public static $key = 'ultraaddons_header_footer';
    public static $data = [
        'header_id' => false,
        'footer_id' =>  false,
        'type'    => 'php' //It will php and css. In
    ];

    public static function init() {
        //add_action( 'get_header', [__CLASS__, 'show_header'], 10, 2 );
        //add_action( 'get_footer', [__CLASS__, 'show_footer'], 10, 2 );
//        add_action( 'wp_body_open', [__CLASS__, 'show_header']);
    }
    
    public static function show_footer( $name, $args ) {
        //var_dump($name, $args);
        ?>
        </div> 
    </body>           
</html>
        <?php
        $templates   = [];
        $templates[] = 'footer.php';
        // Avoid running wp_footer hooks again.
        remove_all_actions( 'wp_footer' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }
    public static function show_header( $name, $args ) {
?>
    
    <h2>ddddddddddddddddddddd</h2>
    <?php
        
        //var_dump($name, $args);
        
        
        $templates   = [];
        $templates[] = 'header.php';
        // Avoid running wp_head hooks again.
        remove_all_actions( 'wp_head' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }
    
    /**
     * Getting templateing type.
     * Such: PHP or CSS
     * 
     * *********************
     * in CSS type:
     * Original header footer will be hide by CSS
     * 
     * in PHP type:
     * template file will be override by our own php header file
     * **********************
     * 
     * @access public
     * 
     * @return string Getting templateing type.
     */
    public static function get_type() {
        $return = 'php';
        $data = self::$data;
        if( isset( $data['type'] ) && !empty( $data['type'] ) ){
            $return = $data['type'];
        }
        
        return apply_filters( 'ultraaddons/header_footer/tepe', $return );
    }
}

Header_Footer::init();