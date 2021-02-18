<?php
namespace UltraAddons\Core;

defined( 'ABSPATH' ) || die();
/**
 * Description of Header_Footer
 *
 * @author CODES
 */
class Header_Footer {
    
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
    <html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div id="page" class="hfeed site">
    <h2>ddddddddddddddddddddd</h2>
    <?php
        
        //var_dump($name, $args);
        (int) $select_post_id = 2304;
        if ( \Elementor\Plugin::instance()->db->is_built_with_elementor( $select_post_id ) ) {
//            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
        }
        
        $templates   = [];
        $templates[] = 'header.php';
        // Avoid running wp_head hooks again.
        remove_all_actions( 'wp_head' );
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }
}

Header_Footer::init();