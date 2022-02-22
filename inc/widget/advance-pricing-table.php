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


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    public function get_style_depends() {
        return ['adv-pricing'];
    }
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
        return [ 'ultraaddons', 'ua', 'price', 'pricing','table' ];
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
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_curreny', [
				'label' => esc_html__( 'Currency Symbol', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$' , 'ultraaddons' ),
				'label_block' => false,
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
			'list_period', [
				'label' => esc_html__( 'Period', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Mo' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'plugin-name' ),
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
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Price List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_price' => esc_html__( 'Price', 'ultraaddons' ),
					],
					[
						'list_price' => esc_html__( 'Price', 'ultraaddons' ),
					],
                    [
						'list_price' => esc_html__( 'Price', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ list_price }}}',
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
        ?>
<section class="pricing-columns pricing-section">
	<div class="toggle-wrap">
		<label class="toggler toggler--is-active" id="filt-monthly">Monthly</label>
		<div class="toggle">
			<input type="checkbox" id="switcher" class="check">
			<b class="b switch"></b>
		</div>
		<label class="toggler" id="filt-hourly">Hourly</label>
	</div>

	<div id="monthly" class="wrapper-full">
		<p class="desc">Pricing in USD. Excludes any applicable tax.</p>
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
					?>
					<div class="plan ua-col-3">
						<div class="price">
							<span class="dollar"><?php echo $item['list_curreny'];?></span>
							<span class="amount"><?php echo $item['list_price'];?></span>
							<span class="slash">/</span>
							<span class="month"><?php echo $item['list_period'];?></span>
						</div>
						<ul>
							<li>30GB<span>SSD Disk</span></li>
							<li>1GB<span>Memory</span></li>
							<li>1 Core<span>vCPU</span></li>
							<li>667GB/mo<span>Transfer</span></li>
						</ul>
						<a class="button sign-up" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($is_external);?> <?php echo esc_attr($nofollow);?>>
							<?php echo $item['list_button'];?>
						</a>
					</div>
					<?php }
				}?>
				</div>
			</div>
		</div>
	</div>

	<!--SECOND PART-->
	<div id="hourly" class="wrapper-full hide">
		<p class="desc">Pricing in USD. Excludes any applicable tax.</p>
		<div id="pricing-chart-wrap">
			<div class="pricing-chart">
				<div id="smaller-plans" class="ua-row">
					<div class="plan ua-col-3">
						<div class="price">
							<span class="dollar">$</span>
							<span class="amount" data-dollar-amount="402.81">402.81</span>
							<span class="slash">/</span>
							<span class="month">mo</span>
						</div>
						<ul>
							<li>80GB<span>SSD Disk</span></li>
							<li>8GB<span>Memory</span></li>
							<li>4 Cores<span>vCPU</span></li>
							<li>5333GB/mo<span>Transfer</span></li>
						</ul>
						<a class="button sign-up"
							href="https://orders.gigenetcloud.com/order.php?quick=79,80,8192,730">Sign
							Up</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
        
    }
    
    
    
    
}