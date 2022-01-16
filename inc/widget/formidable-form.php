<?php
namespace UltraAddons\Widget;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Formidable_Form extends Base{
    
        public function get_keywords() {
                return [ 'ultraaddons', 'appointment', 'contact', 'quote', 'form', 'schedule', 'formidable', 'contact form', ];
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
                $this->register_content_controls();
        }

        protected function register_content_controls(){
                
                $this->start_controls_section(
                        '_section_frm',
                        [
                                'label' =>  __( 'Contact Form', 'ultraaddons' ) ,
                                'tab' => Controls_Manager::TAB_CONTENT,
                        ]
                );
                $this->add_control(
                        'form_id',
                        array(
                        'label'   => __( 'Form', 'ultraaddons' ),
                        'type'    => Controls_Manager::SELECT2,
                        'options' => ultraaddons_get_formidable_forms(),
                        )
                );
                
                $this->add_basic_switcher_control( 'title', __( 'Show Form Title', 'ultraaddons' ) );
                $this->add_basic_switcher_control( 'description', __( 'Show Form Description', 'ultraaddons' ) );
                $this->add_basic_switcher_control( 'minimize', __( 'Minimize HTML', 'ultraaddons' ) );

                $this->end_controls_section();
        }
        private function add_basic_switcher_control( $key, $title ) {
                $this->add_control(
                        $key,
                        array(
                                'label' => $title,
                                'type'  => Controls_Manager::SWITCHER,
                        )
                );
        }
        /**
         * Render widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render() {
                $settings    = $this->get_settings_for_display();

                $form_id     = isset( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;
                $title       = isset( $settings['title'] ) && 'yes' === $settings['title'];
                $description = isset( $settings['description'] ) && 'yes' === $settings['description'];
                $minimize    = isset( $settings['minimize'] ) && 'yes' === $settings['minimize'];
                echo do_shortcode(
                        '[formidable 
                        id="'. $form_id .'" 
                        title="'. $title .'" 
                        description= "'. $description .'" 
                        minimize= "' . $minimize .'" 
                        ]'
                );
        }
}
