<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Plugin;

/**
 * Advance Pricing table
 * Pricing table with css switch toggle
 * Credit: https://codepen.io/kijanmaharjan/pen/dMmdej
 * @since 1.1.0.12
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author B M Rafiul <bmrafiul.alam@gmail.com>
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advance_Pricing_Table extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for pricing
        $ua_name           = 'pricing';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/pricing/js/pricing.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $ua_name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $ua_name );

        $ua_name           = 'modernizr';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'js/modernizr.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $ua_name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $ua_name );

         //CSS file for Slider Script Owl Carousel Slider
        wp_register_style('adv-pricing', ULTRA_ADDONS_ASSETS . 'vendor/pricing/css/pricing.css', array(), ULTRA_ADDONS_VERSION );
        wp_enqueue_style('adv-pricing' );

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
		return [ 'jquery','pricing' ];
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
        return [ 'ultraaddons-elementor-lite', 'ua', 'price', 'pricing','table','advance', 'discount' ];
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
        $this->general_style();
        $this->toggle_style();
        $this->icon_style();
        $this->box_style();
        $this->button_style();
        $this->badge_style();
        $this->discount_style();
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
                'label'     => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'toggle_a', [
				'label' => esc_html__( 'Toggle A Label', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monthly' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$this->add_control(
			'toggle_b', [
				'label' => esc_html__( 'Toggle B Label', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Yearly' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$this->add_control(
			'price_desc', [
				'label' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'	=> 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium <br>doloremque laudantium'
			]
		);
		
		$this->add_control(
			'list_curreny',
			[
				'label' => __( 'Currency Symbol', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'ultraaddons-elementor-lite' ),
					'&#36;' => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#128;' => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#3647;' => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8355;' => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&fnof;' => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'kr' => 'kr ' . _x( 'Krona', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8356;' => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8359;' => '&#8359; ' . _x( 'Peseta', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8369;' => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#163;' => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'R$' => 'R$ ' . _x( 'Real', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8381;' => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8360;' => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8377;' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8362;' => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#165;' => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8361;' => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'&#8378;' => '&#8378; ' . _x( 'Lira (Turkish)', 'Currency Symbol', 'ultraaddons-elementor-lite' ),
					'custom' => __( 'Custom', 'ultraaddons-elementor-lite' ),
				],
				'default' => '&#36;',
			]
		);
		$this->add_control(
			'list_custom_curreny', [
				'label' => esc_html__( 'Custom Currency', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
				'condition'=>[
					'list_curreny' =>'custom'
				]
			]
		);
		$this->add_control(
			'is_back',
			[
				'label' => __( 'Edit Back Part?', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'No', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Basic' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa fa-business-time',
					'library' => 'solid',
				],
			]
		);
	
		$repeater->add_control(
			'list_price', [
				'label' => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '33.99' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'is_discount',
			[
				'label' => __( 'Discount?', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'list_discount_price', [
				'label' => esc_html__( 'Discount Price', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '25.99' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		$repeater->add_control(
			'show_discount',
			[
				'label' => __( 'Show Discount?', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		$repeater->add_control(
			'list_period', [
				'label' => esc_html__( 'Period', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Mo' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'list_feature', [
				'label' => esc_html__( 'Features', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => '<ul>
							<li>80GB<span>SSD Disk</span></li>
							<li>8GB<span>Memory</span></li>
							<li>4 Cores<span>vCPU</span></li>
							<li>5333GB/mo<span>Transfer</span></li>
						</ul>'
			]
		);
		$repeater->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
					'custom_attributes' => '',
				],
                'separator' => 'after'
			]
		);
		$repeater->add_control(
			'list_button', [
				'label' => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Now' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'show_badge',
			[
				'label' => __( 'Show Badge', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'badge_text', [
				'label' => esc_html__( 'Badge Text', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Featured' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
				'condition' =>[
					'show_badge'=>'yes'
				],
				]
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => __( 'Box Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.plan',
			]
		);
		$repeater->add_control(
			'feature_text_color', [
				'label' => __( 'Feature Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .features-list' => 'color: {{VALUE}};',
				],
			]
        );
		$repeater->add_control(
			'box_hover_color', [
				'label' => __( 'Box Hover', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.plan:hover' => 'background-color: {{VALUE}};',
				],
			]
        );
		$repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Title Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .plan-title',
            ]
        );
		$repeater->add_control(
			'title_color', [
				'label' => __( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .plan-title' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater->add_control(
			'amount_color', [
				'label' => __( 'Price Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .amount' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater->add_control(
			'month_color', [
				'label' => __( 'Month Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .month' => 'color: {{VALUE}};',
				],
				'separaor' => 'before',
				'default'=>''
			]
        );
		$repeater->add_control(
			'separator_color', [
				'label' => __( 'Separator Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .slash' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater->add_control(
			'icon_color', [
				'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .pricing-icon-wrapper i' => 'color: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}} .pricing-icon-wrapper svg' => 'fill: {{VALUE}};',
				],
				'default'=>''
			]
        );
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Price List A', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Basic', 'ultraaddons-elementor-lite' ),
					],
					[
						'list_title' => esc_html__( 'Advance', 'ultraaddons-elementor-lite' ),
					],
                    [
						'list_title' => esc_html__( 'Premier', 'ultraaddons-elementor-lite' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$repeater_b = new \Elementor\Repeater();
			$repeater_b->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Basic B' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater_b->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa fa-check-circle',
					'library' => 'solid',
				],
			]
		);
		$repeater_b->add_control(
			'list_price', [
				'label' => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '55.99' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		
		$repeater_b->add_control(
			'is_discount',
			[
				'label' => __( 'Discount?', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',

			]
		);
		$repeater_b->add_control(
			'show_discount',
			[
				'label' => __( 'Show Discount?', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		$repeater_b->add_control(
			'list_discount_price', [
				'label' => esc_html__( 'Discount Price', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '40.99' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		
		$repeater_b->add_control(
			'list_period', [
				'label' => esc_html__( 'Period', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Mo' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater_b->add_control(
			'list_feature', [
				'label' => esc_html__( 'Features', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => '<ul>
							<li>80GB<span>SSD Disk</span></li>
							<li>8GB<span>Memory</span></li>
							<li>4 Cores<span>vCPU</span></li>
							<li>5333GB/mo<span>Transfer</span></li>
						</ul>'
			]
		);
		$repeater_b->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons-elementor-lite' ),
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
					'custom_attributes' => '',
				],
                'separator' => 'after'
			]
		);
		$repeater_b->add_control(
			'list_button', [
				'label' => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Now' , 'ultraaddons-elementor-lite' ),
				'label_block' => false,
			]
		);
		$repeater_b->add_control(
			'show_badge',
			[
				'label' => __( 'Show Badge', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off' => __( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'after'
			]
		);
		$repeater_b->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => __( 'Box Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.plan',
			]
		);
		$repeater_b->add_control(
			'feature_text_color', [
				'label' => __( 'Feature Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .features-list' => 'color: {{VALUE}};',
				],
			]
        );
		$repeater_b->add_control(
			'box_hover_color', [
				'label' => __( 'Box Hover', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.plan:hover' => 'background-color: {{VALUE}};',
				],
			]
        );
		$repeater_b->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Title Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .plan-title',
            ]
        );
		$repeater_b->add_control(
			'title_color', [
				'label' => __( 'Title Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .plan-title' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater_b->add_control(
			'amount_color', [
				'label' => __( 'Price Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .amount' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater_b->add_control(
			'month_color', [
				'label' => __( 'Month Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .month' => 'color: {{VALUE}};',
				],
				'separaor' => 'before',
				'default'=>''
			]
        );
		$repeater_b->add_control(
			'separator_color', [
				'label' => __( 'Separator Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .slash' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater_b->add_control(
			'icon_color', [
				'label' => __( 'Icon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .pricing-icon-wrapper i' => 'color: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}} .pricing-icon-wrapper svg' => 'fill: {{VALUE}};',
				],
				'default'=>''
			]
        );
        $this->add_control(
			'list_b',
			[
				'label' => esc_html__( 'Price List B', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Basic', 'ultraaddons-elementor-lite' ),
						'list_price' => esc_html__( '50', 'ultraaddons-elementor-lite' ),
					],
					[
						'list_title' => esc_html__( 'Advance', 'ultraaddons-elementor-lite' ),
						'list_price' => esc_html__( '70', 'ultraaddons-elementor-lite' ),
					],
                    [
						'list_title' => esc_html__( 'Premier', 'ultraaddons-elementor-lite' ),
						'list_price' => esc_html__( '100', 'ultraaddons-elementor-lite' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);
        $this->end_controls_section();
    }

	protected function general_style() {
        $this->start_controls_section(
            'general_style',
            [
                'label'     => esc_html__( 'General Style', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .plan' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'column_space',
			[
				'label' => esc_html__( 'Column Spacing', 'ultraaddons-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'separator' => 'after',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .ua-col-3' => 'padding:0px {{SIZE}}{{UNIT}} 0px {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Description Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .desc',
            ]
        );
		$this->add_control(
			'desc_color', [
				'label' => __( 'Description Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .desc' => 'color: {{VALUE}};',
				],
				'default'=>'',
			]
        );
		$this->add_responsive_control(
			'desc_margin',
			[
				'label'       => esc_html__( 'Description Margin', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'after',
				'selectors'   => [
					'{{WRAPPER}} .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
	
		$this->add_responsive_control(
			'title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .plan-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Price Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'amount_typography',
                'selector' => '{{WRAPPER}} .amount',
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Discount Price Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'discount_typography',
                'selector' => '{{WRAPPER}} .discount-amount',
            ]
        );
		
		$this->add_responsive_control(
			'price_margin',
			[
				'label'       => esc_html__( 'Price Margin', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'after',
				'selectors'   => [
					'{{WRAPPER}} .plan .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Currency Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'currency_typography',
                'selector' => '{{WRAPPER}} .dollar',
            ]
        );
		$this->add_control(
			'currency_color', [
				'label' => __( 'Currency Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .dollar' => 'color: {{VALUE}};',
				],
				'separator' => 'after',
				'default'=>''
			]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Month Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'month_typography',
                'selector' => '{{WRAPPER}} .month, .slash',
            ]
        );
	
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Featured Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'featured_typography',
                'selector' => '{{WRAPPER}} .features-list',
            ]
        );
        $this->end_controls_section();
    }
	protected function icon_style() {
        $this->start_controls_section(
            'icon_style',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'ultraaddons-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .pricing-icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pricing-icon-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

	
        $this->end_controls_section();
    }
	/**
	 *  Box style Method
	 */
	 protected function box_style(){
       $this->start_controls_section(
            '_ua_card_box_style',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_responsive_control(
			'_ua_box_radius',
			[
				'label'       => esc_html__( 'Box Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .plan' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_box_padding',
			[
				'label'       => esc_html__( 'Box Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .plan' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .plan',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ua_box_border',
				'label' => __( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .plan',
			]
		);
		
	 $this->end_controls_section();
    }

	protected function toggle_style() {
        $this->start_controls_section(
            'toggle_style',
            [
                'label'     => esc_html__( 'Toggle', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'toggle_color', [
				'label' => __( 'Toggle Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .toggle' => 'background: {{VALUE}};',
				],
				'default'=>'#B62347'
			]
        );
		$this->add_control(
			'toggle_text_color', [
				'label' => __( 'Toggle Active Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .toggler.toggler--is-active' => 'color: {{VALUE}};',
				],
				'default'=>'#B62347'
			]
        );
		$this->add_control(
			'toggle_deactive_text_color', [
				'label' => __( 'Toggle De-active Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .toggler' => 'color: {{VALUE}};',
				],
				'default'=>'#ccc'
			]
        );
        $this->end_controls_section();
    }
	/**
	 * Button style Method
	 */
	 protected function button_style(){
       $this->start_controls_section(
            '_ua_card_button_style',
            [
                'label'     => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'style_tabs'
		);
		/**
		 * Normal tab
		 */
		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'card_btn_typography',
					'label' => 'Button Typography',
					'selector' => '{{WRAPPER}} .ua-sign-up',
					'separator'=>'after'
			]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_background',
				'label' => __( 'Button Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ua-sign-up',
			]
		);
		$this->add_control(
			'_ua_btn_text_color', [
				'label' => __( 'Button Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-sign-up' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->add_responsive_control(
			'_ua_card_btn_radius',
			[
				'label'       => esc_html__( 'Button Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-sign-up' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_card_btn_padding',
			[
				'label'       => esc_html__( 'Button Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-sign-up' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'btn_margin',
			[
				'label'       => esc_html__( 'Button Margin', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-sign-up' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'btb_border',
				'label' => __( 'Border', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-sign-up',
			]
		);
		$this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);
		$this->add_control(
			'_ua_btn_text_hover_color', [
				'label' => __( 'Button Text Hover Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-sign-up:hover' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->add_control(
			'_ua_btn_bg_hover_color', [
				'label' => __( 'Button Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-sign-up:hover' => 'background: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->end_controls_tabs();
		
	 $this->end_controls_section();
    }
	/**
	 *  Badge style Method
	 */
	 protected function badge_style(){
       $this->start_controls_section(
            '_badge_style',
            [
                'label'     => esc_html__( 'Badge', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Badge Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .featured-badge',
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'badge_background',
				'label' => __( 'Badge Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .featured-badge',
				// 'exclude' => [ 'image' ],
			]
		);
		$this->add_responsive_control(
			'badge_radius',
			[
				'label'       => esc_html__( 'Badge Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .featured-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'badge_padding',
			[
				'label'       => esc_html__( 'Badge Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .featured-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_shadow',
				'label' => __( 'Badge Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .featured-badge',
			]
		);
		
	 $this->end_controls_section();
    }
	/**
	 *  Discount style Method
	 */
	 protected function discount_style(){
       $this->start_controls_section(
            '_discount_style',
            [
                'label'     => esc_html__( 'Discount', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Discount Typography', 'ultraaddons-elementor-lite' ),
                'name' => 'discount_percent_typography',
                'selector' => '{{WRAPPER}} .discount-percent',
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'discount_background',
				'label' => __( 'Badge Background', 'ultraaddons-elementor-lite' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .discount-percent',
				// 'exclude' => [ 'image' ],
			]
		);
		$this->add_responsive_control(
			'discount_radius',
			[
				'label'       => esc_html__( 'Discount Radius', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .discount-percent' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'discount_padding',
			[
				'label'       => esc_html__( 'Discount Padding', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ '%', 'px' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'=>'before',
				'selectors'   => [
					'{{WRAPPER}} .discount-percent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'discount_shadow',
				'label' => __( 'Discount Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .discount-percent',
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
	$settings           = $this->get_settings_for_display();
	$currency_symbol = ( $settings['list_curreny'] !='custom') ?  $settings['list_curreny'] : $settings['list_custom_curreny'];
	$id= $this->get_id();
	if(Plugin::$instance->editor->is_edit_mode() && $settings['is_back']==='yes'){
		echo '<script>
		jQuery(".monthly").hide();
		jQuery(".hourly").removeClass("hide");
		jQuery(".hourly").show();
		</script>';
	}
	
	?>
<section class="pricing-columns pricing-section adv-pricing-table-<?php echo esc_attr( $id ); ?>">
	<div class="toggle-wrap">
		<label class="toggler toggler--is-active"><?php echo esc_html( $settings['toggle_a'] ); ?></label>
		<div class="toggle">
			<input type="checkbox" class="check switcher">
			<b class="b switch"></b>
		</div>
		<label class="filt-hourly toggler"><?php echo esc_html( $settings['toggle_b'] ); ?></label>
	</div>
	<p class="desc">
		<?php echo wp_kses_data( $settings['price_desc'] ); ?>
	</p>
	<!--Part A-->
	<div class="wrapper-full monthly">
		<div id="pricing-chart-wrap">
			<div class="pricing-chart">
				<div id="smaller-plans" class="ua-row">
					<?php 
					if ( $settings['list'] ) {
						$count=0;
						foreach (  $settings['list'] as $ua_item ) {
							$url		= (!empty( $ua_item['website_link']['url'] )) ? $ua_item['website_link']['url']  : '';
							$is_external 	= ( $ua_item['website_link']['is_external']=='on') ? 'target="_blank"' : '';
							$nofollow 	= ( $ua_item['website_link']['nofollow']=='on') ? 'rel="nofollow"' :'';
							$count=$count+1;
							//Discount Calculate
							$list_price 	=  $ua_item['list_price'] ?? 0;
							$selling_price 	=  $ua_item['list_discount_price'];
							$discount 		= ((float)$list_price - (float)$selling_price);
							if( is_numeric( $list_price ) && is_numeric( $discount ) ){
							$percent 		= ($discount/$list_price) * 100;
							}
							else{
								$percent = 0;
							}

					?>
					<div class="ua-col-3">
						<div class="plan plan-<?php echo esc_attr( $count ); ?> elementor-repeater-item-<?php echo esc_attr( $ua_item['_id'] ); ?>" >
							<?php if($ua_item['show_badge']=='yes'):?>
							<div class="featured-badge">
								<?php echo esc_html( $ua_item['badge_text'] );?>
							</div>
							<?php endif;?>
							<?php
							if('yes'=== $ua_item['show_discount']):
							?>
							<div class="discount-percent">
								<?php
								if( !empty($percent) ){
									 echo esc_html( round( $percent ));
								}
								?>%
							</div>
							<?php endif;?>
							
							<div class="pricing-icon-wrapper">
								<?php \Elementor\Icons_Manager::render_icon( $ua_item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<h2 class="plan-title"><?php echo esc_html( $ua_item['list_title'] ); ?></h2>
							<div class="price">
								<?php
								if('yes'=== $ua_item['is_discount']){
								?>
								<span class="dollar"><?php echo esc_html($currency_symbol);?></span>
								<span class="amount"><s><?php echo esc_html( $list_price ); ?></s></span>
								<?php
								if(!empty($selling_price)):
								?>
								<span class="dollar"><?php echo esc_html($currency_symbol);?></span>
								<span class="discount-amount"><?php echo esc_html($selling_price); ?></span>
								<?php endif;?>
								<?php }else{?>
									<span class="dollar"><?php echo esc_html($currency_symbol);?></span>
								<span class="amount"><?php echo esc_html( $ua_item['list_price'] );?></span>
								<?php }?>
							   <?php if( !empty($ua_item['list_period']) ):?>
								<span class="slash">/</span>
								<?php endif;?>
								<span class="month"><?php echo esc_html($ua_item['list_period']);?></span>
							</div>
							<div class="features-list"><?php echo wp_kses_post($ua_item['list_feature']);?></div>
							<a class="button ua-sign-up" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?>>
								<?php echo esc_html($ua_item['list_button']);?>
							</a>
						</div>
					</div>
					<?php }
				}?>
				</div>
			</div>
		</div>
	</div>

	<!-- PART B-->
	<div class="wrapper-full hourly hide">
		<div id="pricing-chart-wrap">
			<div class="pricing-chart">
				<div class="ua-row">
					<?php 
					if ( $settings['list_b'] ) {
						$count=0;
						foreach (  $settings['list_b'] as $ua_item ) {
							$url		= (!empty( $ua_item['website_link']['url'] )) ? $ua_item['website_link']['url']  : '';
							$is_external 	= ( $ua_item['website_link']['is_external']=='on') ? 'target="_blank"' : '';
							$nofollow 	= ( $ua_item['website_link']['nofollow']=='on') ? 'rel="nofollow"' :'';
							$count=$count+1;
							//Discount Calculate
							$list_price 	=  $ua_item['list_price'];
							$selling_price 	=  $ua_item['list_discount_price'];
							$discount 		= ((float)$list_price - (float)$selling_price);

							if( $list_price != 0 ){
								$percent 		= ($discount/$list_price) * 100;
							}
					?>
					<div class="ua-col-3">
						<div class="plan plan-<?php echo esc_attr($count);?> elementor-repeater-item-<?php echo esc_attr( $ua_item['_id'] ); ?>">
							<?php if($ua_item['show_badge']=='yes'):?>
							<div class="featured-badge">
								<?php echo esc_html($ua_item['badge_text']) ;?>
							</div>
							<?php endif;?>
							<?php
							if('yes'=== $ua_item['show_discount']):
							?>
							<div class="discount-percent">
								<?php 
								if( !empty($percent) ){
									echo esc_html( round($percent));
								}
								?>%
								
							</div>
							<?php endif;?>

							<div class="pricing-icon-wrapper">
								<?php \Elementor\Icons_Manager::render_icon( $ua_item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<h2 class="plan-title"><?php echo esc_html($ua_item['list_title']);?></h2>
							<div class="price">
								<?php
								if('yes'=== $ua_item['is_discount']){
								?>
								<span class="dollar"><?php echo esc_html($currency_symbol);?></span>
								<span class="amount"><s><?php echo esc_html($list_price); ?></s></span>
								<?php
								if(!empty($selling_price)):
								?>
								<span class="dollar"><?php echo esc_html($currency_symbol);?></span>
								<span class="discount-amount"><?php echo esc_html($selling_price); ?></span>
								<?php endif;?>
								<?php }else{?>
									<span class="dollar"><?php echo esc_html($currency_symbol);?></span>
								<span class="amount"><?php echo esc_html($ua_item['list_price']);?></span>
								<?php }?>
							   <?php if( !empty($ua_item['list_period']) ):?>
								<span class="slash">/</span>
								<?php endif;?>
								<span class="month"><?php echo esc_html($ua_item['list_period']);?></span>
							</div>
							<div class="features-list"><?php echo wp_kses_post($ua_item['list_feature']);?></div>
							<a class="button ua-sign-up" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?>>
								<?php echo esc_html($ua_item['list_button']);?>
							</a>
						</div>
					</div>
					<?php }
					}?>
				</div>
			</div>
		</div>
	</div>
	
</section>

<?php
        
    } 
    
}