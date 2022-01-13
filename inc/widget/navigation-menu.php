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
        $this->nav_style();
        $this->drop_down_style();
        $this->navbar_toggle_style();
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
                '{{WRAPPER}} .ua-navigation-container' => 'justify-content: {{VALUE}};',
            ],
        ]
    );
    $this->add_control(
        'submenu_icon',
        [
            'label'        => __( 'Submenu Icon', 'ultraaddons' ),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'arrow-small',
            'options'      => [
                'arrow-small'   => __( 'Arrows Small', 'ultraaddons' ),
                'arrow-big'   => __( 'Arrows BIg', 'ultraaddons' ),
                'plus'    => __( 'Plus Sign', 'ultraaddons' ),
            ],
        ]
    );

    $this->add_control(
        'submenu_animation',
        [
            'label'        => __( 'Submenu Animation', 'ultraaddons' ),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'none',
            'options'      => [
                'none'     => __( 'Default', 'ultraaddons' ),
                'slide_up' => __( 'Slide Up', 'ultraaddons' ),
            ],
            'prefix_class' => '',
        ]
    );
   /*  $this->add_control(
        'breakpoint',
        [
            'label'        => __( 'Breakpoint', 'ultraaddons' ),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'mobile',
            'options'      => [
                '768' => __( 'Mobile (768px >)', 'ultraaddons' ),
                '1025' => __( 'Tablet (1025px >)', 'ultraaddons' ),
                'none'   => __( 'None', 'ultraaddons' ),
            ],
        ]
    ); */

$this->end_controls_section();

}

protected function nav_style() {
    $this->start_controls_section(
        'nav_style',
        [
            'label'     => esc_html__( 'General Style', 'ultraaddons' ),
            'tab'       => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'nav_bg_color',
        [
            'label'     => __( 'Background Color', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .ua.navbar ul' => 'background-color: {{VALUE}}',
            ],
        ]
    );
    
    $this->add_responsive_control(
        'nav_padding',
        [
            'label'       => esc_html__( 'Navigation Padding', 'ultraaddons' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => [ 'px', '%' ],
            'placeholder' => [
                'top'    => '',
                'right'  => '',
                'bottom' => '',
                'left'   => '',
            ],
            'selectors'   => [
                '{{WRAPPER}} .ua.navbar ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'     => 'nav_typography',
            'label'    => __( 'Typography', 'ultraaddons' ),
            'global'   => [
                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
            ],
            'selector' => '{{WRAPPER}} .ua.navbar ul li a',
        ]
    );

    $this->add_control(
        'nav_color',
        [
            'label'     => __( 'Link Text Color', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111',
            'selectors' => [
                '{{WRAPPER}} .ua.navbar ul li a' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->start_controls_tabs(
        'style_tabs'
    );
    //Normal Tab
    $this->start_controls_tab(
        'btn_normal_tab',
        [
            'label' => esc_html__( 'Normal', 'ultraaddons' ),
        ]
    );
    $this->add_control(
        'nav_hover',
        [
            'label'     => __( 'Link Hover Effect', 'ultraaddons' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'none',
            'options'   => [
                'none'        => __( 'None', 'ultraaddons' ),
                'stroke'   => __( 'Stroke', 'ultraaddons' ),
                'fill'    => __( 'Fill', 'ultraaddons' ),
                'circle' => __( 'Circle', 'ultraaddons' ),
                'shift'      => __( 'Shif', 'ultraaddons' ),
                'text'        => __( 'Text', 'ultraaddons' ),
            ],
        ]
    );
    $this->add_control(
        'nav_hover_effect_color',
        [
            'label'     => __( 'Hover Effect Color', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111',
            'selectors' => [
                '{{WRAPPER}} .stroke .nav li a:after, .fill .nav li a:after, .shift .nav li a:after' => 'background: {{VALUE}} !important',
            ],
        ]
    );
    $this->end_controls_tab();

    //Hover Tab
    $this->start_controls_tab(
        'btn_hover_tab',
        [
            'label' => esc_html__( 'Hover', 'ultraaddonse' ),
        ]
    );
    $this->add_control(
        'nav_hover_color',
        [
            'label'     => __( 'Hover Color', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111',
            'selectors' => [
                '{{WRAPPER}} .ua.navbar ul li a:hover' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->end_controls_tab();
    $this->end_controls_tabs();
   
    $this->end_controls_section();
}

protected function drop_down_style() {

    $this->start_controls_section(
        'dropdown_style',
        [
            'label'     => esc_html__( 'Dropdown', 'ultraaddons' ),
            'tab'       => Controls_Manager::TAB_STYLE,
        ]
    );
    
    $this->add_responsive_control(
        'dropdown_padding',
        [
            'label'       => esc_html__( 'Navigation Padding', 'ultraaddons' ),
            'type'        => Controls_Manager::DIMENSIONS,
            'size_units'  => [ 'px', '%' ],
            'placeholder' => [
                'top'    => '',
                'right'  => '',
                'bottom' => '',
                'left'   => '',
            ],
            'selectors'   => [
                '{{WRAPPER}} .ua.navbar .subnav li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this->add_control(
        'nav_sub_menu_bg',
        [
            'label'     => __( 'Sub Menu Background', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F9F9F9',
            'selectors' => [
                '{{WRAPPER}} .ua.navbar .subnav' => 'background: {{VALUE}}',
            ],
        ]
    );
    $this->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name' => 'nav_sub_menu_border',
            'label' => __( 'Border', 'ultraaddons' ),
            'selector' => '{{WRAPPER}}  .navbar.open-mobile .nav li',
        ]
    );
    $this->end_controls_section();
}

protected function navbar_toggle_style() {

    $this->start_controls_section(
        'toggle_style',
        [
            'label'     => esc_html__( 'Navbar Toggle', 'ultraaddons' ),
            'tab'       => Controls_Manager::TAB_STYLE,
        ]
    );
    $this->add_control(
        'nav_toggle_align',
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
                ]
            ],
            'default'      => 'left',
            'selectors' => [
                '{{WRAPPER}} .ua.navbar' => 'justify-content: {{VALUE}};',
            ],
        ]
    );
    $this->add_control(
        'nav_toggle_bg',
        [
            'label'     => __( 'Button Background', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fff',
            'selectors' => [
                '{{WRAPPER}} .navbar-toggle' => 'background: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'nav_toggle_menu_bg',
        [
            'label'     => __( 'Dropdown Background', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fff',
            'selectors' => [
                '{{WRAPPER}} .ua.navbar.open-mobile .nav' => 'background: {{VALUE}}',
                '{{WRAPPER}} .ua.navbar.open-mobile .nav li ul.subnav' => 'background: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'nav_toggle_menu_color',
        [
            'label'     => __( 'Dropdown Text Color', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#222',
            'selectors' => [
                '{{WRAPPER}} .navbar.open-mobile .nav li a' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'toggle_icon',
        [
            'label' => esc_html__( 'Icon', 'ultraaddons' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-bars',
                'library' => 'solid',
            ],
            'separator' => 'before'
        ]
    );
    $this->add_control(
        'nav_toggle_icon_color',
        [
            'label'     => __( 'Icon Color', 'ultraaddons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#222',
            'selectors' => [
                '{{WRAPPER}} .navbar-toggle i' => 'color: {{VALUE}}',
            ],
        ]
    );
    $this->add_control(
        'toggle_icon_size',
        [
            'label' => esc_html__( 'Icon Size', 'ultraaddons' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 200,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 25,
            ],
            'selectors' => [
                '{{WRAPPER}} .navbar-toggle i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                var $openClass = "open open-position";
                var $hasChildren = "menu-item-has-children";
                if ($hasChildren.length > 0) {
                  jQuery(".navbar").on("click", "." + $hasChildren, function () {
                    jQuery(this).toggleClass($openClass);
                  });
                }
                jQuery(".navbar-toggle").on("click", function () {
                    jQuery(".ua.navbar").toggleClass("open-mobile");
                  });
            </script>';
        }
        $this->add_render_attribute(
            'ua-nav-menu',
            'class',
            [
                'ua-menu-wrap icon-' . $settings['submenu_icon'] .' '. $settings['nav_hover'],
            ]
        );
    
        //$breakpoint = ($settings['breakpoint']!='none') ? $settings['breakpoint'] : '' ;
       
        ?>
        <nav class="ua navbar" data-function="navbar" data-breakpoint="<?php echo esc_attr($breakpoint);?>" data-toggle-siblings="true" data-delay="500" aria-label="Main">
            <button class="navbar-toggle" aria-haspopup="true" aria-expanded="false" <?php esc_attr_e( 'Toggle navigation','ultraaddons' ); ?>>
            <?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </button>
            <div <?php echo $this->get_render_attribute_string( 'ua-nav-menu' ); ?>>
                <?php
				if ( $get_menu_id ) :
					wp_nav_menu( array(
                        'menu'              => $get_menu_id,
						'depth'             => 3,
						'menu_class'        => 'nav',
                        'container_class'   => 'ua-navigation-container',
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
