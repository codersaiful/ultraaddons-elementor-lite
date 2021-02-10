<?php
namespace UltraAddons\Widget;


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

class Button extends Widget_Base{
    
    /**
     * Widget Pricing Table
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Widget Name.
     */
    public function get_name() {
        return strtolower( str_replace( '\\','_', __CLASS__ ) );
        //return __CLASS__;
    }
    
    /**
     * Widget Title.
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Widget Title.
     */
    public function get_title() {
        return __( 'UltraAddons Button', 'medilac' );
    }
  
    /**
     * Help URL
     *
     * @since 1.0.0
     *
     * @var int Widget Icon.
     */
    public function get_custom_help_url() {
            return 'https://example.com/Medilac_Button';
    }
    
    /**
     * Widget Icon.
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-button';
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
        return [ 'ultraaddons', 'button', 'btn', 'bt', 'recent content' ];
    }
    /**
     * Widget Category.
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Widget Category.
     */
    public function get_categories() {
        return [ 'basic' ];
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

       
        //For Design Section Style Tab
        $this->style_design_controls();
        
        //For Typography Section Style Tab
        $this->style_typography_controls();

       
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
        
        
        $settings           = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'medilac-button-wrapper' );
        $this->add_render_attribute( 'button', 'class', 'medilac-button' );
       
        if ( ! empty( $settings['link']['url'] ) ) {
                $this->add_link_attributes( 'button', $settings['link'] );
                $this->add_render_attribute( 'button', 'class', 'medilac-button-link' );
        }

        $this->add_render_attribute( 'button', 'role', 'button' );
        $this->add_render_attribute( 'button', 'class', $settings['size'] );
        $this->add_render_attribute( 'wrapper', 'class', $settings['button_type'] );

        
        if( ! isset( $settings['text'] ) ){
            return;
        }
        $this->add_inline_editing_attributes( 'text', 'none' );
        $text = ! empty( $settings['text'] ) ? $settings['text'] : __( 'Click Here', 'medilac' );
        
        
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
          <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
              <?php echo esc_html( $text ); ?>
          </a>
        </div>
        <?php

    }
    
    protected function _content_template() {
        /*
        ?>
        <#
        view.addInlineEditingAttributes( 'avd_heading', 'none' );
        view.addInlineEditingAttributes( 'avd_sub_heading', 'none' );
        #>
        
        <div class="advance-heading-wrapper">
            <span {{{ view.getRenderAttributeString( 'avd_sub_heading' ) }}}>{{{ settings.avd_sub_heading }}}</span>
            <h4 class="heading-tag" {{{ view.getRenderAttributeString( 'avd_heading' ) }}}>{{{ settings.avd_heading }}}</h4>
        </div>
        <?php
        */
    }
    
    /**
     * Get button sizes.
     *
     * Retrieve an array of button sizes for the button widget.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array An array containing button sizes.
     */
    public static function get_button_sizes() {
            return [
                    'xs' => __( 'Extra Small', 'medilac' ),
                    'sm' => __( 'Small', 'medilac' ),
                    'md' => __( 'Medium', 'medilac' ),
                    'lg' => __( 'Large', 'medilac' ),
                    'xl' => __( 'Extra Large', 'medilac' ),
            ];
    }
    
    /**
     * General Section for Content Controls
     * 
     * @since 1.0.0.9
     */
    protected function content_general_controls() {
        $this->start_controls_section(
                'section_button',
                [
                        'label' => __( 'Button', 'medilac' ),
                ]
        );

        $this->add_control(
                'button_type',
                [
                        'label' => __( 'Type', 'medilac' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                                'left'    => [
                                        'title' => __( 'Left', 'medilac' ),
                                        'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                        'title' => __( 'Center', 'medilac' ),
                                        'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                        'title' => __( 'Right', 'medilac' ),
                                        'icon' => 'eicon-text-align-right',
                                ],
                                'fullwidth' => [
                                        'title' => __( 'Fullwidth', 'medilac' ),
                                        'icon' => 'eicon-text-align-justify',
                                ],
                        ],
                        'default' => 'left',
                        'toggle'    => false,
                
                ]
        );

        $this->add_control(
			'size',
			[
				'label' => __( 'Size', 'medilac' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

        $this->add_control(
                'text',
                [
                        'label' => __( 'Text', 'medilac' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'default' => __( 'Click here', 'medilac' ),
                        'placeholder' => __( 'Click here', 'medilac' ),
                ]
        );

        $this->add_control(
                'link',
                [
                        'label' => __( 'Link', 'medilac' ),
                        'type' => Controls_Manager::URL,
                        'dynamic' => [
                                'active' => true,
                        ],
                        'placeholder' => __( 'https://your-link.com', 'medilac' ),
                        'default' => [
                                'url' => '#',
                        ],
                ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Finding Category list as Array
     * What to show as Option for Query Section
     * 
     * @since 1.0.0.9
     */
    protected function get_cat_as_options(){
        $args = [
            'orderby'   =>  'count',
            'hide_empty'=>  0
        ];
        $categories = get_terms( 'category', $args );
        
        $options = [];
        if( is_array( $categories ) && count( $categories ) > 0 ){
            foreach( $categories as $category ){
                $options[$category->term_id]  = $category->name;
            }
        }

        return $options;
    }
    
    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        $this->start_controls_section(
            'design',
            [
                'label'     => esc_html__( 'Design', 'medilac' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        
        $this->add_control(
            'primary-color',
            [
                'label'     => __( 'Primary Color', 'medilac' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .medilac-button-wrapper .medilac-button' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .medilac-button-wrapper .medilac-button:hover' => 'background-color: transparent;border-color: {{VALUE}};color: {{VALUE}}',
                ],
                'default'   => '#0FC392',
            ]
        );
        
        
        $this->end_controls_section();
    }
    
    /**
     * Typography Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_typography_controls() {
        $this->start_controls_section(
            'mc_rc_typography',
            [
                'label'     => esc_html__( 'Typography', 'medilac' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'typography',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],
                        'selector' => '{{WRAPPER}} .medilac-button',
                ]
        );
        
        $this->end_controls_section();
    }
    
    
}