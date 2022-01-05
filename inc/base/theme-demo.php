<?php 
namespace UltraAddons\Base;

use UltraAddons\Library\Demo\Theme_Demo as Base_Theme_Demo;

/**
 * Theme's Demo Manager Helper Class
 * No one call from any where of UltraAddons inside
 * It will be called directly from theme
 * 
 * Actually we would like to handle it from Theme
 * If any user want to handle Demo manager from his theme.
 * 
 * @since 1.1.0.9
 * @author Saiful Islam <codersaiful@gmail.com>
 * 
 * @package UltraAddons
 */
class Theme_Demo extends Base_Theme_Demo{

    /**
     * Contstruct of base Theme Demo Class
     * 
     * @since 1.1.0.9
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     */
    public function __construct()
    {
        parent::$_instance;
    }

    
}