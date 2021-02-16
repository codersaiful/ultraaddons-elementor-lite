<?php

namespace UltraAddons;

defined('ABSPATH') || exit;

/**
 * Autoloader for UltraAddons Elementor Lite. All class will call from here by AutoLoader
 *
 * @author Saiful Islam<codersaiful@gmail.com>
 * @since 1.0
 * @package UltraAddons
 * @link https://www.php.net/manual/en/language.oop5.autoload.php Autoloader Function
 */
class Autoloader {
    
    /**
     * Obviously should be lower char
     *
     * @var type Array
     */
    private static $parent_namespace = ['modules','includes','assets'];

    /**
     * Run autoloader.
     *
     * Register a function as `__autoload()` implementation.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function run() {
        //var_dump(__CLASS__);
        spl_autoload_register([ __CLASS__ , 'autoload' ]);
    }

    /**
     * Autoload.
     *
     * For a given class, check if it exist and load it.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @param string $class Class name.
     */
    private static function autoload( $class ) {
        var_dump( $class );
        //var_dump( strpos('abc','babcocdeabcd') ,__NAMESPACE__, $class);
        if (0 !== strpos( $class, __NAMESPACE__ ) ) {
            return;
        }


        $filename = strtolower(
                preg_replace(
                        ['/\b' . __NAMESPACE__ . '\\\/', '/_/', '/\\\/'], ['', '-', DIRECTORY_SEPARATOR], $class
                )
        );
        
        $full_class_name = strtolower(preg_replace('/\\\/', DIRECTORY_SEPARATOR, $class));
        

        
//        $extra_dir = false;
//        $core_class = explode( DIRECTORY_SEPARATOR , $filename );
//        if (0 < strpos($full_class_name, 'widgets' . DIRECTORY_SEPARATOR) || 0 < strpos($full_class_name, 'controls' . DIRECTORY_SEPARATOR)) {
//            $extra_dir = 'modules' . DIRECTORY_SEPARATOR;
//        }elseif( is_array($core_class) && ( count( $core_class ) == 1 || ( count( $core_class ) >= 2 && !in_array( $core_class[0], self::$parent_namespace ) ) ) ){
//            $extra_dir = 'core' . DIRECTORY_SEPARATOR;
//        }
//        //var_dump($core_class);
//        
//        $filename = ULTRA_ELEMENTOR_PATH . $extra_dir . $filename . '.php'; //plugin_dir_path(__DIR__)
//        //var_dump( $class .  " ====  " . $filename, $core_class);
//        if ( file_exists( $filename ) ) {
//            require_once $filename;
//        }

    }

}

Autoloader::run();