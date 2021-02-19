<?php
namespace UltraAddons\Classes;

/**
 * Getting Elementor Template List
 * 
 * @since 1.0.1.0
 */
class Template_List {
    public $templates = [];
    
    public function __construct() {
        
    }
    
    public function get_templates() {

        $args = array(
            'post_type'     =>  'elementor_library',
            'post_status'   =>  'publish'
        );
        $query = get_posts( $args );

        // Check, If post not found , Direct return main choicese
        if( empty( $query ) ){
            $this->templates = [];
        }

        //If found post, then itarate
        if( is_array( $query ) && count( $query ) > 0 ){
            foreach( $query as $q_post ){
                $id = (int) $q_post->ID;
                $this->templates[$id] = $q_post->post_title;
            }
        }
        
        return $this->templates;
    }
}
