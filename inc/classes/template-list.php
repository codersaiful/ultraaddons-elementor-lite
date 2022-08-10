<?php
namespace UltraAddons\Classes;

use UltraAddons\WP\Header_Footer_Post as HF_Post;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Getting template list 
 * which we will create
 * on post type header_footer
 * 
 * @since 1.0.1.0
 */
class Template_List {
    public $templates = [];
    
    public function __construct() {
        
    }
    
    public function get_templates() {

        $args = array(
            'post_type'     =>  HF_Post::$post_type,
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
