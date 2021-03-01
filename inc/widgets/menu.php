<?php
namespace UltraAddons\Widget;

use UltraAddons\Classes\Menu_Walker;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Menu extends Base{
    
    /**
     * Set your widget name keyword
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'menu', 'nav', 'navbar', 'menubar','navigation' ];
    }
    
    /**
     * Whether the reload preview is required or not.
     *
     * Used to determine whether the reload preview is required.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool Whether the reload preview is required.
     */
    public function is_reload_preview_required() {
            return true;
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

       
        //General Control of Style for menu
        $this->content_general_style();
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
        $settings = $this->get_settings_for_display();
        
        //First Check, Menu Already Available or not
        $menu = $this->get_available_menus();

        if( empty( $menu ) ){
            echo wp_kses_post( '<h2 class="hidden_text">' . __( 'There is no menu available. Please create First' ) . '</h2>' );
            return;
        }
        
        $this->add_render_attribute( 'wrapper', 'class', [
            'ultraaddons-navigation-wrapper',
            'ultraaddons-navigation',
            'ultraaddons-navigation' . $this->get_id(),
        ] );
        $this->add_render_attribute( 'wrapper', 'id', 'ultraaddons-navigation' );
        $menu_id = $settings['menu'];

        $args = array(
		'menu'                 => $menu_id,
		'container'            => 'div',
		'container_class'      => 'ua-menu-wrapper',
		'container_id'         => '',
		'container_aria_label' => '',
		'menu_class'           => 'ua-menu',
		'menu_id'              => '',
		'echo'                 => false,
		'fallback_cb'          => '__return_empty_string',//'wp_page_menu',
		'before'               => '',
		'after'                => '',
		'link_before'          => '',
		'link_after'           => '',
		'items_wrap'           => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'item_spacing'         => 'preserve',
		'depth'                => 0,
		//'walker'               => new Menu_Walker(),
		//'theme_location'       => '',
	);
        $menu_html = wp_nav_menu( $args );
        $menu_text = $settings['menu_text'];//esc_html_e( 'Primary Menu', 'ultraaddons' );

        
        //Handle Icon
        $has_icon = ! empty( $settings['add_icon'] );

        if ( $has_icon ) {
                $this->add_render_attribute( 'i', 'class', $settings['add_icon'] );
                $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
        }
        $svg        = !empty( $settings['add_add_icon']['value']['url'] ) && is_string( $settings['add_add_icon']['value']['url'] ) ? $settings['add_add_icon']['value']['url'] : false;

        ?>
        <nav <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-nav-inside">
                <button class="ua-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <?php if( $svg ){ ?>
                    <img class="ua-menu-icon-svg" src="<?php echo esc_url( $svg ); ?>">
                    <?php }elseif( $has_icon ){ ?>
                    <i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
                    <?php } ?>
                    <span><?php echo $menu_text; ?></span>
                </button>
                <?php echo wp_kses_post( $menu_html ); ?>
            </div>
            
        </nav>
        <?php

    }
    
    /**
     * Retrieve the list of available menus.
     *
     * Used to get the list of available menus.
     *
     * @since 1.3.0
     * @access private
     *
     * @return array get WordPress menus list.
     */
    private function get_available_menus() {

            $menus = wp_get_nav_menus();

            $options = [];

            foreach ( $menus as $menu ) {
                    $options[ $menu->slug ] = $menu->name;
            }

            return $options;
    }

    /**
     * General Style Section for Content Controls
     * 
     * @since 1.0.2.1
     */
    protected function content_general_style(){
            $this->start_controls_section(
                    'general_style',
                    [
                            'label' => __( 'General', 'ultraaddons' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );
            
            
            $this->add_control(
                    'template',
                    [
                            'label'     => __( 'Background', 'ultraaddons' ),
                            'type'      => Controls_Manager::SELECT,
                            'options'   => [
                                'default'   => __( 'Normal', 'ultraaddons' ),
                                'temp-2'   => __( 'Template 2', 'ultraaddons' ),
                                'temp-3'   => __( 'Template 3', 'ultraaddons' ),
                            ],
                            'default'      => 'normal',
                            'prefix_class' => 'ua-menu-temp-'
                    ]
            );
            
            
            
            $this->end_controls_section();
    }
    
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
                'general',
                [
                        'label' => __( 'General', 'ultraaddons' ),
                ]
        );
        
        $menus = $this->get_available_menus();
        
        if ( ! empty( $menus ) ) {
                $this->add_control(
                        'menu',
                        [
                                'label'        => __( 'Menu', 'ultraaddons' ),
                                'type'         => Controls_Manager::SELECT,
                                'options'      => $menus,
                                'default'      => array_keys( $menus )[0],
                                'save_default' => true,
                                'separator'    => 'after',
                                /* translators: %s Nav menu URL */
                                'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'ultraaddons' ), admin_url( 'nav-menus.php' ) ),
                        ]
                );
        } else {
                $this->add_control(
                        'menu',
                        [
                                'type'            => Controls_Manager::RAW_HTML,
                                /* translators: %s Nav menu URL */
                                'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'ultraaddons' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                                'separator'       => 'after',
                                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                        ]
                );
        }
        
        
        $this->add_control(
                'sub_menu_type',
                [
                        'label'        => __( 'Sub Menu Type', 'ultraaddons' ),
                        'type'         => Controls_Manager::SELECT,
                        'options'      => [
                            'normal'    => __( 'Default', 'ultraaddons' ),
                            'fullwidth'    => __( 'Full Width Flex', 'ultraaddons' ),
                            'mega'    => __( 'Mega', 'ultraaddons' ),
                        ],
                        'default'      => 'normal',
                        'save_default' => true,
                        'prefix_class'  => 'submenu-type-',
                ]
        );
        
        $this->add_control(
                    'add_icon',
                    [
                            'label' => __( 'Menu Icon', 'ultraaddons' ),
                            'type' => Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'default' => [
                                    'value' => 'fas fa-shopping-cart', //<i class="fas fa-shopping-cart"></i>
                                    'library' => 'fa-solid',
                            ],
                            'description'   => esc_html__( 'Only for Mobile Menu, If any user want to show rext for Mobile' ),
                            
                    ]
            );
        
        
        
        $this->add_control(
                'menu_text',
                [
                        'label'        => __( 'Menu Text', 'ultraaddons' ),
                        'type'         => Controls_Manager::TEXT,
                        'placeholder'  => __( 'Primary Menu', 'ultraaddons' ),
                        'default'      => __( 'Primary Menu', 'ultraaddons' ),//'Primary Menu',
                        'save_default' => true,
                        'description'   => esc_html__( 'Only for Mobile Menu, If any user want to show rext for Mobile' ),
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    
}