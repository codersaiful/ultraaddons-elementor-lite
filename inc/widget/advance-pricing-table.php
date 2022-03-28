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

/**
 * Class Description den Noman vai
 * 
 * @author Saiful Islam <codersaiful@gmail.com>
 * @since 1.1.0.12
 * 
 */
class Advance_Pricing_Table extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        //Naming of Args for pricing
        $name           = 'pricing';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/pricing/js/pricing.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

        $name           = 'modernizr';
        $js_file_url    = ULTRA_ADDONS_ASSETS . 'js/modernizr.js';
        $dependency     =  ['jquery'];//['jquery'];
        $version        = ULTRA_ADDONS_VERSION;
        $in_footer  = true;

        wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
        wp_enqueue_script( $name );

         //CSS file for Slider Script Owl Carousel Slider
        wp_register_style('adv-pricing', ULTRA_ADDONS_ASSETS . 'vendor/pricing/css/pricing.css' );
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
        return [ 'ultraaddons', 'ua', 'price', 'pricing','table','advance', 'discount' ];
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
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		$this->add_control(
			'toggle_a', [
				'label' => esc_html__( 'Toggle A Label', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monthly' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$this->add_control(
			'toggle_b', [
				'label' => esc_html__( 'Toggle B Label', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Yearly' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$this->add_control(
			'price_desc', [
				'label' => esc_html__( 'Description', 'ultraaddons' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'	=> 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium <br>doloremque laudantium'
			]
		);
		
		$this->add_control(
			'list_curreny',
			[
				'label' => __( 'Currency Symbol', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'ultraaddons' ),
					'&#36;' => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'ultraaddons' ),
					'&#128;' => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'ultraaddons' ),
					'&#3647;' => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'ultraaddons' ),
					'&#8355;' => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'ultraaddons' ),
					'&fnof;' => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'ultraaddons' ),
					'kr' => 'kr ' . _x( 'Krona', 'Currency Symbol', 'ultraaddons' ),
					'&#8356;' => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'ultraaddons' ),
					'&#8359;' => '&#8359; ' . _x( 'Peseta', 'Currency Symbol', 'ultraaddons' ),
					'&#8369;' => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'ultraaddons' ),
					'&#163;' => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'ultraaddons' ),
					'R$' => 'R$ ' . _x( 'Real', 'Currency Symbol', 'ultraaddons' ),
					'&#8381;' => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'ultraaddons' ),
					'&#8360;' => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'ultraaddons' ),
					'&#8377;' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'ultraaddons' ),
					'&#8362;' => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'ultraaddons' ),
					'&#165;' => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'ultraaddons' ),
					'&#8361;' => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'ultraaddons' ),
					'&#8378;' => '&#8378; ' . _x( 'Lira (Turkish)', 'Currency Symbol', 'ultraaddons' ),
					'custom' => __( 'Custom', 'ultraaddons' ),
				],
				'default' => '&#36;',
			]
		);
		$this->add_control(
			'list_custom_curreny', [
				'label' => esc_html__( 'Custom Currency', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$' , 'ultraaddons' ),
				'label_block' => false,
				'condition'=>[
					'list_curreny' =>'custom'
				]
			]
		);
		$this->add_control(
			'is_back',
			[
				'label' => __( 'Edit Back Part?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before'
			]
		);
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Basic' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa fa-business-time',
					'library' => 'solid',
				],
			]
		);
	
		$repeater->add_control(
			'list_price', [
				'label' => esc_html__( 'Price', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '33.99' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'is_discount',
			[
				'label' => __( 'Discount?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'list_discount_price', [
				'label' => esc_html__( 'Discount Price', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '25.99' , 'ultraaddons' ),
				'label_block' => false,
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		$repeater->add_control(
			'show_discount',
			[
				'label' => __( 'Show Discount?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		$repeater->add_control(
			'list_period', [
				'label' => esc_html__( 'Period', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Mo' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'list_feature', [
				'label' => esc_html__( 'Features', 'ultraaddons' ),
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
				'label' => esc_html__( 'Link', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons' ),
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
				'label' => esc_html__( 'Button Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Now' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'show_badge',
			[
				'label' => __( 'Show Badge', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'badge_text', [
				'label' => esc_html__( 'Badge Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Featured' , 'ultraaddons' ),
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
				'label' => __( 'Box Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.plan',
			]
		);
		$repeater->add_control(
			'feature_text_color', [
				'label' => __( 'Feature Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .features-list' => 'color: {{VALUE}};',
				],
			]
        );
		$repeater->add_control(
			'box_hover_color', [
				'label' => __( 'Box Hover', 'ultraaddons' ),
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
				'label'     => esc_html__( 'Title Typography', 'ultraaddons' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .plan-title',
            ]
        );
		$repeater->add_control(
			'title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
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
				'label' => __( 'Price Color', 'ultraaddons' ),
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
				'label' => __( 'Month Color', 'ultraaddons' ),
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
				'label' => __( 'Separator Color', 'ultraaddons' ),
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
				'label' => __( 'Icon Color', 'ultraaddons' ),
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
				'label' => esc_html__( 'Price List A', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Basic', 'ultraaddons' ),
					],
					[
						'list_title' => esc_html__( 'Advance', 'ultraaddons' ),
					],
                    [
						'list_title' => esc_html__( 'Premier', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$repeater_b = new \Elementor\Repeater();
			$repeater_b->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Basic B' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater_b->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa fa-check-circle',
					'library' => 'solid',
				],
			]
		);
		$repeater_b->add_control(
			'list_price', [
				'label' => esc_html__( 'Price', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '55.99' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		
		$repeater_b->add_control(
			'is_discount',
			[
				'label' => __( 'Discount?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',

			]
		);
		$repeater_b->add_control(
			'show_discount',
			[
				'label' => __( 'Show Discount?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		$repeater_b->add_control(
			'list_discount_price', [
				'label' => esc_html__( 'Discount Price', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '40.99' , 'ultraaddons' ),
				'label_block' => false,
				'condition' =>[
					'is_discount'=>'yes'
				],
			]
		);
		
		$repeater_b->add_control(
			'list_period', [
				'label' => esc_html__( 'Period', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Mo' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater_b->add_control(
			'list_feature', [
				'label' => esc_html__( 'Features', 'ultraaddons' ),
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
				'label' => esc_html__( 'Link', 'ultraaddons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'ultraaddons' ),
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
				'label' => esc_html__( 'Button Text', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy Now' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater_b->add_control(
			'show_badge',
			[
				'label' => __( 'Show Badge', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'after'
			]
		);
		$repeater_b->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => __( 'Box Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.plan',
			]
		);
		$repeater_b->add_control(
			'feature_text_color', [
				'label' => __( 'Feature Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .features-list' => 'color: {{VALUE}};',
				],
			]
        );
		$repeater_b->add_control(
			'box_hover_color', [
				'label' => __( 'Box Hover', 'ultraaddons' ),
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
				'label'     => esc_html__( 'Title Typography', 'ultraaddons' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .plan-title',
            ]
        );
		$repeater_b->add_control(
			'title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
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
				'label' => __( 'Price Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .amount' => 'color: {{VALUE}};',
				],
				'default'=>''
			]
        );
		$repeater_b->add_control(
			'month_color', [
				'label' => __( 'Month Color', 'ultraaddons' ),
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
				'label' => __( 'Separator Color', 'ultraaddons' ),
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
				'label' => __( 'Icon Color', 'ultraaddons' ),
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
				'label' => esc_html__( 'Price List B', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Basic', 'ultraaddons' ),
						'list_price' => esc_html__( '50', 'ultraaddons' ),
					],
					[
						'list_title' => esc_html__( 'Advance', 'ultraaddons' ),
						'list_price' => esc_html__( '70', 'ultraaddons' ),
					],
                    [
						'list_title' => esc_html__( 'Premier', 'ultraaddons' ),
						'list_price' => esc_html__( '100', 'ultraaddons' ),
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
                'label'     => esc_html__( 'General Style', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
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
				'label' => esc_html__( 'Column Spacing', 'ultraaddons' ),
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
				'label'     => esc_html__( 'Description Typography', 'ultraaddons' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .desc',
            ]
        );
		$this->add_control(
			'desc_color', [
				'label' => __( 'Description Color', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Description Margin', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
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
					'{{WRAPPER}} .plan-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Price Typography', 'ultraaddons' ),
                'name' => 'amount_typography',
                'selector' => '{{WRAPPER}} .amount',
            ]
        );
		
		$this->add_responsive_control(
			'price_margin',
			[
				'label'       => esc_html__( 'Price Margin', 'ultraaddons' ),
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
					'{{WRAPPER}} .amount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Currency Typography', 'ultraaddons' ),
                'name' => 'currency_typography',
                'selector' => '{{WRAPPER}} .dollar',
            ]
        );
		$this->add_control(
			'currency_color', [
				'label' => __( 'Currency Color', 'ultraaddons' ),
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
				'label'     => esc_html__( 'Month Typography', 'ultraaddons' ),
                'name' => 'month_typography',
                'selector' => '{{WRAPPER}} .month',
            ]
        );
	
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Featured Typography', 'ultraaddons' ),
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
                'label'     => esc_html__( 'Icon', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'ultraaddons' ),
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
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_responsive_control(
			'_ua_box_radius',
			[
				'label'       => esc_html__( 'Box Radius', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Box Padding', 'ultraaddons' ),
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
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .plan',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ua_box_border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .plan',
			]
		);
		
	 $this->end_controls_section();
    }

	protected function toggle_style() {
        $this->start_controls_section(
            'toggle_style',
            [
                'label'     => esc_html__( 'Toggle', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'toggle_color', [
				'label' => __( 'Toggle Background', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .toggle' => 'background: {{VALUE}};',
				],
				'default'=>'#B62347'
			]
        );
		$this->add_control(
			'toggle_text_color', [
				'label' => __( 'Toggle Active Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .toggler.toggler--is-active' => 'color: {{VALUE}};',
				],
				'default'=>'#B62347'
			]
        );
		$this->add_control(
			'toggle_deactive_text_color', [
				'label' => __( 'Toggle De-active Text Color', 'ultraaddons' ),
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
                'label'     => esc_html__( 'Button', 'ultraaddons' ),
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
				'label' => __( 'Normal', 'ultraaddons' ),
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
				'label' => __( 'Button Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ua-sign-up',
			]
		);
		$this->add_control(
			'_ua_btn_text_color', [
				'label' => __( 'Button Text Color', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Button Radius', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Button Padding', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Button Margin', 'ultraaddons' ),
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
				'label' => __( 'Border', 'ultraaddons' ),
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
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
		$this->add_control(
			'_ua_btn_text_hover_color', [
				'label' => __( 'Button Text Hover Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-sign-up:hover' => 'color: {{VALUE}};',
				],
				'separator'=>'before'
			]
        );
		$this->add_control(
			'_ua_btn_bg_hover_color', [
				'label' => __( 'Button Background', 'ultraaddons' ),
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
                'label'     => esc_html__( 'Badge', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Badge Typography', 'ultraaddons' ),
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .featured-badge',
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'badge_background',
				'label' => __( 'Badge Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .featured-badge',
				'exclude' => [ 'image' ],
			]
		);
		$this->add_responsive_control(
			'badge_radius',
			[
				'label'       => esc_html__( 'Badge Radius', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Badge Padding', 'ultraaddons' ),
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
				'label' => __( 'Badge Shadow', 'ultraaddons' ),
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
                'label'     => esc_html__( 'Discount', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
				'label'     => esc_html__( 'Discount Typography', 'ultraaddons' ),
                'name' => 'discount_typography',
                'selector' => '{{WRAPPER}} .discount-percent',
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'discount_background',
				'label' => __( 'Badge Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .discount-percent',
				'exclude' => [ 'image' ],
			]
		);
		$this->add_responsive_control(
			'discount_radius',
			[
				'label'       => esc_html__( 'Discount Radius', 'ultraaddons' ),
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
				'label'       => esc_html__( 'Discount Padding', 'ultraaddons' ),
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
				'label' => __( 'Discount Shadow', 'ultraaddons' ),
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
	
	if(Plugin::$instance->editor->is_edit_mode() && $settings['is_back']==='yes'){
		echo '<script>
		jQuery(".monthly").hide();
		jQuery(".hourly").removeClass("hide");
		jQuery(".hourly").show();
		</script>';
	}
	?>
<section class="pricing-columns pricing-section">
	<div class="toggle-wrap">
		<label class="toggler filt-monthly toggler--is-active"><?php echo $settings['toggle_a'] ?></label>
		<div class="toggle">
			<input type="checkbox" class="check switcher">
			<b class="b switch"></b>
		</div>
		<label class="toggler filt-hourly"><?php echo $settings['toggle_b'] ?></label>
	</div>
	<p class="desc">
		<?php echo $settings['price_desc']; ?>
	</p>
	<!--Part A-->
	<div class="wrapper-full monthly">
		<div id="pricing-chart-wrap">
			<div class="pricing-chart">
				<div id="smaller-plans" class="ua-row">
					<?php 
					if ( $settings['list'] ) {
						$count=0;
						foreach (  $settings['list'] as $item ) {
							$url		= (!empty( $item['website_link']['url'] )) ? $item['website_link']['url']  : '';
							$is_external 	= ( $item['website_link']['is_external']=='on') ? 'target="_blank"' : '';
							$nofollow 	= ( $item['website_link']['nofollow']=='on') ? 'rel="nofollow"' :'';
							$count=$count+1;
							//Discount Calculate
							$list_price 	=  $item['list_price'];
							$selling_price 	=  $item['list_discount_price'];
							$discount 		= ((float)$list_price - (float)$selling_price);
							if( $list_price != 0 ){
							$percent 		= ($discount/$list_price) * 100;
							}

					?>
					<div class="ua-col-3">
						<div class="plan plan-<?php echo $count;?> <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ) . ''?>" >
							<?php if($item['show_badge']=='yes'):?>
							<div class="featured-badge">
								<?php echo $item['badge_text'] ;?>
							</div>
							<?php endif;?>
							<?php
							if('yes'=== $item['show_discount']):
							?>
							<div class="discount-percent">
								<?php
								if( !empty($percent) ){
									 echo round($percent);
								}
								?>%
							</div>
							<?php endif;?>
							
							<div class="pricing-icon-wrapper">
								<?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<h2 class="plan-title"><?php echo $item['list_title'];?></h2>
							<div class="price">
								<?php
								if('yes'=== $item['is_discount']){
								?>
								<span class="dollar"><?php echo $currency_symbol;?></span>
								<span class="amount"><s><?php echo $list_price; ?></s></span>

								<span class="dollar"><?php echo $currency_symbol;?></span>
								<span class="discount-amount"><?php echo $selling_price; ?></span>
								<?php }else{?>
									<span class="dollar"><?php echo $currency_symbol;?></span>
								<span class="amount"><?php echo $item['list_price'];?></span>
								<?php }?>
							   <?php if( !empty($item['list_period']) ):?>
								<span class="slash">/</span>
								<?php endif;?>
								<span class="month"><?php echo $item['list_period'];?></span>
							</div>
							<div class="features-list"><?php echo $item['list_feature'];?></div>
							<a class="button ua-sign-up" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?>>
								<?php echo $item['list_button'];?>
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
						foreach (  $settings['list_b'] as $item ) {
							$url		= (!empty( $item['website_link']['url'] )) ? $item['website_link']['url']  : '';
							$is_external 	= ( $item['website_link']['is_external']=='on') ? 'target="_blank"' : '';
							$nofollow 	= ( $item['website_link']['nofollow']=='on') ? 'rel="nofollow"' :'';
							$count=$count+1;
							//Discount Calculate
							$list_price 	=  $item['list_price'];
							$selling_price 	=  $item['list_discount_price'];
							$discount 		= ((float)$list_price - (float)$selling_price);

							if( $list_price != 0 ){
								$percent 		= ($discount/$list_price) * 100;
							}
					?>
					<div class="ua-col-3">
						<div class="plan plan-<?php echo $count;?> <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ) . ''?>">
							<?php if($item['show_badge']=='yes'):?>
							<div class="featured-badge">
								<?php echo $item['badge_text'] ;?>
							</div>
							<?php endif;?>
							<?php
							if('yes'=== $item['show_discount']):
							?>
							<div class="discount-percent">
								<?php 
								if( !empty($percent) ){
									echo round($percent);
								}
								?>%
								
							</div>
							<?php endif;?>

							<div class="pricing-icon-wrapper">
								<?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<h2 class="plan-title"><?php echo $item['list_title'];?></h2>
							<div class="price">
								<?php
								if('yes'=== $item['is_discount']){
								?>
								<span class="dollar"><?php echo $currency_symbol;?></span>
								<span class="amount"><s><?php echo $list_price; ?></s></span>

								<span class="dollar"><?php echo $currency_symbol;?></span>
								<span class="discount-amount"><?php echo $selling_price; ?></span>
								<?php }else{?>
								<span class="dollar"><?php echo $currency_symbol;?></span>
								<span class="amount"><?php echo $item['list_price'];?></span>
								<?php }?>
							
								<?php if( !empty($item['list_period']) ):?>
								<span class="slash">/</span>
								<?php endif;?>
								<span class="month"><?php echo $item['list_period'];?></span>
							</div>
							<div class="features-list"><?php echo $item['list_feature'];?></div>
							<a class="button ua-sign-up" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?>>
								<?php echo $item['list_button'];?>
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