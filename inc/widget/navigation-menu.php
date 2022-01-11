<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Navigation_Menu extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for Skill Chart
        $name           = 'navbarjs';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/navbar/js/navbar.min.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

         //CSS file News Ticker
         wp_register_style('navbarjs', ULTRA_ADDONS_ASSETS . 'vendor/navbar/css/navbar.css' );
         wp_enqueue_style('navbarjs' );
    }
	

    /**
     * Retrieve the list of scripts the skill bar widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.9.2
     * @access public
     *
     * @return array Widget scripts dependencies.
     * @by Saiful
     */
    public function get_script_depends() {
		return [ 'jquery','navbarjs' ];
    }
    public function get_style_depends() {
		return [ 'navbarjs' ];
    }
    /**
     * Get your widget name
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'nav', 'menu', 'navigation' ];
    }
    
    
    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        
        //For General Section
        $this->content_general_controls();
        $this->layout_controls();
    }
        
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'general_content',
            [
                'label'     => esc_html__( 'Menu', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        $menus = $this->get_menus();
        if ( ! empty( $menus ) ) {
            $this->add_control(
                'menu',[
                    'label'        => __( 'Menu', 'ultraaddons' ),
                    'type'         => Controls_Manager::SELECT,
                    'options'      => $menus,
                    'default'      => array_keys( $menus )[0],
                    'save_default' => true,
                    'separator'    => 'after',
                    'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'ultraaddons' ), admin_url( 'nav-menus.php' ) ),
                ]
            );
    } else {
        $this->add_control(
            'menu_error',[
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'ultraaddons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                'separator'       => 'after',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );
    }
        
        $this->end_controls_section();
    }

protected function layout_controls() {  
    $this->start_controls_section(
        'section_layout',
        [
            'label' => __( 'Layout', 'ultraaddons' ),
        ]
    );


    $this->add_control(
        'navmenu_align',
        [
            'label'        => __( 'Alignment', 'ultraaddons' ),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'left'    => [
                    'title' => __( 'Left', 'ultraaddons' ),
                    'icon'  => 'eicon-h-align-left',
                ],
                'center'  => [
                    'title' => __( 'Center', 'ultraaddons' ),
                    'icon'  => 'eicon-h-align-center',
                ],
                'right'   => [
                    'title' => __( 'Right', 'ultraaddons' ),
                    'icon'  => 'eicon-h-align-right',
                ],
                'justify' => [
                    'title' => __( 'Justify', 'ultraaddons' ),
                    'icon'  => 'eicon-h-align-stretch',
                ],
            ],
            'default'      => 'left',
            'selectors' => [
                '{{WRAPPER}} .menu-main-container' => 'justify-content: {{VALUE}};',
            ],
        ]
    );


$this->end_controls_section();
}

     /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings    = $this->get_settings_for_display();
        $get_menu_id = $settings['menu'];

        if(Plugin::$instance->editor->is_edit_mode()){
            echo '<script>
                new Navbar("ul.nav");
            </script>';
        }
      
        ?>
        <nav class="navbar" data-function="navbar" data-breakpoint="768" data-toggle-siblings="true" data-delay="500" aria-label="Main">
            <button class="navbar-toggle" aria-haspopup="true" aria-expanded="false" <?php esc_attr_e( 'Toggle navigation','ultraaddons' ); ?>> <!-- your responsive toggle button comes here -->
            <i class="menu-icon eicon-menu-bar" aria-hidden="true"></i>
            </button>
            <div class="ua-menu-wrap"> <!-- this wrapper contains the menu and other contents -->
                <?php
				if ( $get_menu_id ) :
					wp_nav_menu( array(
                        'menu'              => $get_menu_id,
						'depth'             => 3,
						'menu_class'        => 'nav',
						'items_wrap'		=> '<ul class="nav" data-function="navbar">%3$s</ul>',
					) );
			    endif; 
                ?>
            </div>
        </nav>
      
    <?php
    }
    //Get Menu List
    private function get_menus() {

        $menus      = wp_get_nav_menus();
        $options    = [];
        foreach ( $menus as $menu ) {
            $options[ $menu->slug ] = $menu->name;
        }
        return $options;
    }
    
    
    
    
}
