<?php
namespace UltraAddons\Widget;

use UltraAddons\Classes\Menu_Walker;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
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
        return [ 'ultraaddons', 'ua','menu', 'nav', 'navbar', 'menubar','navigation' ];
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
    protected function register_controls() {
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
        $wrapper_id = 'ua-navigation-' . $this->get_id();
        $this->add_render_attribute( 'wrapper', 'class', [
            'ua-navigation',
            $wrapper_id,
        ] );
        $this->add_render_attribute( 'wrapper', 'id', $wrapper_id );
        $menu_id = $settings['menu'];

        $args = array(
		'menu'                 => $menu_id,
		'container'            => 'div',
		'container_class'      => 'ua-menu-container',
		'container_id'         => '',
		'container_aria_label' => '',
		'menu_class'           => 'ua-nav-menu',
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
        $svg        = !empty( $settings['add_icon']['value']['url'] ) && is_string( $settings['add_icon']['value']['url'] ) ? $settings['add_icon']['value']['url'] : false;

        ?>
        <nav <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ua-nav-inside">
                <button class="ua-menu-toggle" aria-controls="<?php echo esc_attr( $wrapper_id ); ?>" aria-expanded="false">
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
<script type="text/javascript" id="ua-menu-">
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
        navigationHandle( '<?php echo esc_html( $wrapper_id ); ?>' );
	function navigationHandle( element_id ){
            
            //element_id = 'site-navigation'
            const siteNavigation = document.getElementById( element_id );

            // Return early if the navigation don't exist.
            if ( ! siteNavigation ) {
                    return;
            }

            const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

            // Return early if the button don't exist.
            if ( 'undefined' === typeof button ) {
                    return;
            }

            const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

            // Hide menu toggle button if menu is empty and return early.
            if ( 'undefined' === typeof menu ) {
                    button.style.display = 'none';
                    return;
            }

            if ( ! menu.classList.contains( 'nav-menu' ) ) {
                    menu.classList.add( 'nav-menu' );
            }

            // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
            button.addEventListener( 'click', function() {
                    siteNavigation.classList.toggle( 'expanded' );

                    if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
                            button.setAttribute( 'aria-expanded', 'false' );
                    } else {
                            button.setAttribute( 'aria-expanded', 'true' );
                    }
            } );

            // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
            document.addEventListener( 'click', function( event ) {
                    const isClickInside = siteNavigation.contains( event.target );

                    if ( ! isClickInside ) {
                            siteNavigation.classList.remove( 'expanded' );
                            button.setAttribute( 'aria-expanded', 'false' );
                    }
            } );

            // Get all the link elements within the menu.
            const links = menu.getElementsByTagName( 'a' );

            // Get all the link elements with children within the menu.
            const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );
            
            jQuery('#' + element_id + ' .menu-item-has-children > a, .page_item_has_children > a').after('<i class="available-submenu"></i>');

            // Toggle focus each time a menu link is focused or blurred.
            for ( const link of links ) {
//                    link.addEventListener( 'focus', toggleFocus, true );
                    link.addEventListener( 'blur', toggleFocus, true );
            }

            // Toggle focus each time a menu link with children receive a touch event.
            for ( const link of linksWithChildren ) {
//                    link.addEventListener( 'touchstart', toggleFocus, true );
            }
            jQuery('#' + element_id).on('click', '.available-submenu', function(e){
                e.preventDefault();
                jQuery(this).closest('.menu-item-has-children').toggleClass('focus');
                //jQuery(this).closest('.menu-item-has-children').toggleClass('expand');
            });
           
            /**
             * Sets or removes .focus class on an element.
             */
            function toggleFocus() {
                    if ( event.type === 'focus' || event.type === 'blur' || event.type === 'touchstart' ) {
                            let self = this;
                            // Move up through the ancestors of the current link until we hit .nav-menu.
                            while ( ! self.classList.contains( 'nav-menu' ) ) {
                                    // On li elements toggle the class .focus.
                                    if ( 'li' === self.tagName.toLowerCase() ) {
                                            self.classList.toggle( 'focus' );
                                    }
                                    self = self.parentNode;
                            }
                    }

//                    if ( event.type === 'touchstart' ) {
//                            const menuItem = this.parentNode;
//                            event.preventDefault();
//                            for ( const link of menuItem.parentNode.children ) {
//                                    if ( menuItem !== link ) {
//                                            link.classList.remove( 'focus' );
//                                    }
//                            }
//                            menuItem.classList.toggle( 'focus' );
//                    }
            }
            
            
        }
}() );
</script>
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
        
        $this->add_control(
                'mega_menu',
                [
                        'label' => __( 'General Mega Menu', 'ultraaddons' ),
                        'description' => __( 'All submenu will display in two line', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'On', 'ultraaddons' ),
                        'label_off' => __( 'Off', 'ultraaddons' ),
                        'return_value' => 'yes',
                        'default' => '',
                        'prefix_class' => 'mega-menu-'
                ]
        );
        
        $this->end_controls_section();
    }
    
    
}