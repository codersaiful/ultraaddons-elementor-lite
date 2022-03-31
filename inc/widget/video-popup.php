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
/**
     * Modal Video
    
    * Accessible for keyboard navigation and screen readers.
    * Rich options for youtube API and Vimeo API

    * @source: https://github.com/appleple/modal-video
    * @since 1.1.0.12
    * @package UltraAddons
    * @author Saiful islam <codersaiful@gmail.com>
    * @author B M Rafiul <bmrafiul.alam@gmail.com>
 */

class Video_Popup extends Base{

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

          //Naming of Args for Modal-video
          $name           = 'modal-video';
          $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/modal-video/js/modal-video.min.js';
          $dependency     =  ['jquery'];//['jquery'];
          $version        = ULTRA_ADDONS_VERSION;
          $in_footer      = true;
  
          wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
          wp_enqueue_script( $name );
  

         //CSS file for dependency
		$name           = 'modal-video';
        $css_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/modal-video/css/modal-video.min.css';
        $dependency     =  [];//kaj ta ses hoyni. pore abar try korte hobe.
        $version        = ULTRA_ADDONS_VERSION;
        $media  	= 'all';
        wp_register_style('modal-video', $css_file_url,$dependency,$version, $media ); //modal-video
        wp_enqueue_style('modal-video' );
    }
	

    /**
     * Retrieve the list of scripts the Video Popup widget depended on.
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
		return [ 'jquery', 'modal-video' ];
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
        return [ 'ultraaddons','ua', 'modal', 'video', 'popup' ];
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
            'video_id', [
                    'label' => __( 'Youtube ID', 'ultraaddons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => 'n_ea3devnlg',
                    'label_block' => false,
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
        echo '<script>
        window.addEventListener("DOMContentLoaded",function(){
            new ModalVideo(".js-modal-btn");
        });
        </script>';
        ?>
        <button class="js-modal-btn"  data-video-id="<?php echo $settings['video_id']; ?>">Open Vimeo</button>
        <?php
        
    }
    
    
    
    
}
