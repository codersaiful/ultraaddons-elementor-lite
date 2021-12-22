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

class Portfolio_Gallery extends Base{
    
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
        return [ 'ultraaddons','ua', 'portfolio', 'photo', 'gallery','filter' ];
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
        <section class="portfolio-gallery">
  <ul>
    <li class="list active" data-filter="all">All</li>
    <li class="list" data-filter="photoshop">Photoshop</li>
    <li class="list" data-filter="illustrator">Illustrator</li>
    <li class="list" data-filter="HTML- CSS">HTML & CSS</li>
    <li class="list" data-filter="javascript">Javascript</li>
    <li class="list" data-filter="Bootstrap">Bootstrap</li>
  <li class="list" data-filter="reactJS">ReactJS</li>
      </ul>
  <div class="product">
    <div class="itemsbox photoshop">
      <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox illustrator">
      <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox photoshop">
      <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox HTML-CSS">
      <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox javascript">
      <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
     <div class="itemsbox bootstrap">
      <img src="https://images.unsplash.com/photo-1436076863939-06870fe779c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox reactjs">
      <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1652&q=80">
    </div>
     <div class="itemsbox photoshop">
      <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox illustrator">
      <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox photoshop">
      <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox HTML-CSS">
      <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox javascript">
      <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
     <div class="itemsbox bootstrap">
      <img src="https://images.unsplash.com/photo-1436076863939-06870fe779c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80">
    </div>
    <div class="itemsbox reactjs">
      <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1652&q=80">
    </div>
  </div>
</section>

        <?php
        
    }
     
    
}
