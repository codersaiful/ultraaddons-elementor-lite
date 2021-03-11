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

class Search_Box extends Base{
    
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
        return [ 'ultraaddons', 'cart', 'wc', 'woocommerce', 'minicart', 'mini cart' ];
    }
    
    
    /**
     * Register Control Handle from Here
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @author Saiful
     */
    protected function _register_controls() {

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

        /**
         * If not found WooCommerce
         */
        if ( ! class_exists( 'WooCommerce' ) ) {
        ?>
        <h3><?php echo esc_html__( 'WooComemrce is not activated.', 'ultraaddons' ); ?></h3>
        <?php    
            return;
        }
        
        
        $this->add_render_attribute( 'wrapper', 'class', 'ultraaddons-cart-wrapper' );
        $this->add_render_attribute( 'cart_link', 'class', 'cart-link-li' );
        
        $this->add_render_attribute( 'cart', 'class', 'site-elementor-cart' );
        $this->add_render_attribute( 'cart', 'id', 'site-elementor-cart' );
        
        
        $title = 'My Cart'; //empty minimum
        
        
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            
            <ul <?php echo $this->get_render_attribute_string( 'cart' ); ?>>
			<li <?php echo $this->get_render_attribute_string( 'cart_link' ); ?>>
				<?php ultraaddons_woocommerce_cart_link(); ?>
			</li>
                        <li class="minicart-content-wrapper">
				<?php
                                /**
                                 * Do Insert something at the Top of the Mincart
                                 */
                                do_action( 'ultraaddons_minicart_top' );
                                
				$instance = array(
					//'title' => esc_html( 'My Cart', 'ultraaddons' ),
					'title' => $title,
				);
                                $instance = apply_filters( 'ultraaddons_minicart_args', $instance );
				the_widget( 'WC_Widget_Cart', $instance );
                                
                                /**
                                 * Do Insert something at the Top of the Mincart
                                 */
                                do_action( 'ultraaddons_minicart_bottom' );
                                
				?>
			</li>
		</ul>
        </div>
        <?php

    }
    
    
    
}