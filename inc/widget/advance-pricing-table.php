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
		<p class="bandwidth">Pricing in USD. Excludes any applicable tax.</p>
		<div id="pricing-chart-wrap">
			<div class="pricing-chart">
				<div id="smaller-plans" class="ua-row">
					<div class="plan ua-col-3">
						<div class="price">
							<span class="dollar">$</span>
							<span class="amount" data-dollar-amount="35.49">35.49</span>
							<span class="slash">/</span>
							<span class="month">mo</span>
						</div>
						<ul>
							<li>30GB<span>SSD Disk</span></li>
							<li>1GB<span>Memory</span></li>
							<li>1 Core<span>vCPU</span></li>
							<li>667GB/mo<span>Transfer</span></li>
						</ul>
						<a class="button sign-up"
							href="https://orders.gigenetcloud.com/order.php?quick=79,30,1024,730">Sign Up</a>
					</div>
					<div class="plan popular ua-col-3">
						<div class="price">
							<span class="dollar">$</span>
							<span class="amount" data-dollar-amount="56.88">56.88</span>
							<span class="slash">/</span>
							<span class="month">mo</span>
						</div>
						<p class="pop-plan">Most Popular Plan</p>
						<ul>
							<li>40GB<span>SSD Disk</span></li>
							<li>2GB<span>Memory</span></li>
							<li>1 Core<span>vCPU</span></li>
							<li>1333GB/mo<span>Transfer</span></li>
						</ul>
						<a class="button sign-up"
							href="https://orders.gigenetcloud.com/order.php?quick=79,40,2048,730">Sign Up</a>
					</div>
					<div class="plan ua-col-3">
						<div class="price">
							<span class="dollar">$</span>
							<span class="amount" data-dollar-amount="202.81">202.81</span>
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
							href="https://orders.gigenetcloud.com/order.php?quick=79,80,8192,730">Sign Up</a>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!--SECOND PART-->
	<div id="hourly" class="wrapper-full hide">
		<p class="bandwidth">Pricing in USD. Excludes any applicable tax.</p>
		<div id="pricing-chart-wrap">
			<div class="pricing-chart">
				<div id="smaller-plans" class="ua-row">
					<div class="plan ua-col-3">
						<div class="price">
							<span class="dollar">$</span>
							<span class="amount" data-dollar-amount="25.49">25.49</span>
							<span class="slash">/</span>
							<span class="month">mo</span>
						</div>
						<ul>
							<li>30GB<span>SSD Disk</span></li>
							<li>1GB<span>Memory</span></li>
							<li>1 Core<span>vCPU</span></li>
							<li>667GB/mo<span>Transfer</span></li>
						</ul>
						<a class="button sign-up"
							href="https://orders.gigenetcloud.com/order.php?quick=79,30,1024,730">Sign
							Up</a>
					</div>
					<div class="plan popular ua-col-3">
						<div class="price">
							<span class="dollar">$</span>
							<span class="amount" data-dollar-amount="100.88">100.88</span>
							<span class="slash">/</span>
							<span class="month">mo</span>
						</div>
						<p class="pop-plan">Most Popular Plan</p>
						<ul>
							<li>40GB<span>SSD Disk</span></li>
							<li>2GB<span>Memory</span></li>
							<li>1 Core<span>vCPU</span></li>
							<li>1333GB/mo<span>Transfer</span></li>
						</ul>
						<a class="button sign-up"
							href="https://orders.gigenetcloud.com/order.php?quick=79,40,2048,730">Sign
							Up</a>
					</div>
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

	</div>
</section>

<?php
        
    }
    
    
    
    
}