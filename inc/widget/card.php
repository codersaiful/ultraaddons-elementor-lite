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
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Card Widget
 * Create Incredibly powerful widget to demonstrate your products, articles, news, creative posts using a beautiful combination of texts, links, badge and image. 

 * @since 1.1.0.7
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */
class Card extends Base{
	
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
        return [ 'ultraaddons', 'ua', 'Card', 'profile', 'info box' ];
    }
	
	 /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        //For Content Section
        $this->ticker_content_controls();
        //For Design Section Style Tab
   
        //For Setting Control
        //$this->ticker_settings_controls();
		
		//For Style Control
        //$this->ticker_style_controls();
		//For Btn Control
        //$this->ticker_btn_style_controls();
    }
	/**
	 * Here should comment actually
	 * 
	 * It's actually content control part
	 */
	protected function ticker_content_controls() {
		
        $this->start_controls_section(
		
            '_ua_card_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
		
		
	$this->end_controls_section();
	}
    /**
     * Retrive setting for news tricker control
     * 
     * @author Saiful <codersaiful@gmail.com>
     *
     * @return void
     */
    protected function ticker_settings_controls(){
        $this->start_controls_section(
            '_ua_card_style',
            [
                'label'     => esc_html__( 'Card Style', 'ultraaddons' ),
            ]
        );


        $this->end_controls_section();
    }

	
	
   protected function render() {
		$settings 				=	$this->get_settings_for_display();
	?>
	<div class="ua-card-content">
	  <div class="ua-card">
		<div class="ua-card-header">
		  <div class="ua-card-avatar-content">
		  <a class="ua-card-avatar-link" href="#"><img class="avatar" src="https://randomuser.me/api/portraits/women/47.jpg"/></a></div>
		  <div class="ua-card-title">Natalia Brown</div>
		  <div class="ua-card-subtitle">Telephone operator</div>
		</div>
		<div class="ua-card-body">
		  <div class="ua-card-heading">New card design</div>
		  <p class="ua-card-text">Minim dolor in amet nulla laboris enim dolore consequat proident fugiat culpa eiusmod.</p>
		</div>
		<div class="ua-card-footer">
		  <button>View profile</button>
		</div>
	  </div>
	</div>
<?php }
}