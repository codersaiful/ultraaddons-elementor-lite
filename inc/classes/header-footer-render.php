<?php
namespace UltraAddons\Classes;

use UltraAddons\Core\Header_Footer;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Header_Footer_Render {
    public static $heder_footer_data = [];
    public static $template_loc = [];
    public static $pagewise_template = [];
    public static $template_datas = null;
    
    /**
	 * Current page type
	 *
	 * @since  1.0.0
	 *
	 * @var $current_page_type
	 */
	private static $current_page_type = null;

    /**
	 * CUrrent page data
	 *
	 * @since  1.0.0
	 *
	 * @var $current_page_data
	 */
	private static $current_page_data = array();

    
    public static function init( $heder_footer_data ){
        
        $heder_footer_data = array_filter($heder_footer_data,function($item){
            return ! empty( $item );
        });
        if( ! is_array( $heder_footer_data ) ) return;
        
        self::$heder_footer_data = $heder_footer_data;
        $locs =[]; // $wasys = 
        foreach(self::$heder_footer_data as $key=>$eack ){
            $position = $eack['position'] ?? false;
            // $way = isset($eack['way']) ? 'css' : 'direct';
            // $wasys[$key]=$way;
            $locs[$position]= $key;
        }

        if(empty($locs)) return;

        
        $header = $locs['header'] ?? false;
        $before_header = $locs['before_header'] ?? false;
        $footer = $locs['footer'] ?? false;
        $after_footer = $locs['after_footer'] ?? false;
        
        /**
         * This is curently off actually
         * I will enable it later.
         * It's actually will be use for Canvas
         * 
         * Right now, I will opp this option from Admin Panel
         * 
         * Pore jokhon chalu korbo, tokhon canvas on offer vittite data khuje niye chalu korbo
         * 
         */
        // add_action( 'wp', [__CLASS__, 'test_func'] );
        
        
        if( $header ){
            add_action( 'get_header', [__CLASS__, 'get_header'], 10, 2 );
        }
        
        if( $before_header ){
            add_action( 'wp_body_open', [__CLASS__, 'add_header'] );
            add_filter( 'body_class', [__CLASS__, 'header_css_body_class'] );
        }
        // var_dump($footer);
        if( $footer ){
            add_action( 'get_footer', [__CLASS__, 'get_footer'], 10, 2 );
        }
        
        if( $after_footer ){
            add_action( 'wp_footer', [__CLASS__, 'add_footer'] );
            add_filter( 'body_class', [__CLASS__, 'header_css_body_class'] );
        }

        //Totally complete, I added all templates css file
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

    public static function test_func(){
        self::conditional_set_template_data();
        $canvas = get_post_meta(self::get_header_id(),'display-on-canvas-template',true);
        $canvas = ! empty( $canvas ) ? true : false;
        
        if ( $canvas ) {
            add_action( 'elementor/page_templates/canvas/before_content', [ __CLASS__, 'get_canvas_header' ] );
        }else{
            add_action( 'get_header', [__CLASS__, 'get_header'], 10, 2 );
        }
        
    }

    public static function get_canvas_header(){
        self::conditional_set_template_data();
        echo ultraaddons_elementor_display_content( self::get_header_id() );
    }
    public static function add_header(){
        self::conditional_set_template_data();
        echo ultraaddons_elementor_display_content( self::get_before_header_id() );
    }
    public static function header_css_body_class( $body_class ){
        $body_class[] = 'ua-header-type-css';
        return $body_class;
    }

    public static function add_footer(){
        self::conditional_set_template_data();
        echo ultraaddons_elementor_display_content( self::get_after_footer_id() );
    }
    public static function footer_css_body_class( $body_class ){
        $body_class[] = 'ua-footer-type-css';
        return $body_class;
    }

    public static function get_header($name, $args){

        self::conditional_set_template_data();

        if(self::get_header_id()){
            self::set_template('header');
        }
        
    }
    public static function get_footer($name, $args){

        self::conditional_set_template_data();

        if(self::get_footer_id()){
            self::set_template('footer');
        }
        
    }

    public static function get_header_id(){
        self::conditional_set_template_data();
        
        return self::$template_datas['header_id'] ?? 0;
    }
    public static function get_before_header_id(){
        self::conditional_set_template_data();
        return self::$template_datas['before_header_id'] ?? 0;
    }

    public static function get_footer_id(){
        
        self::conditional_set_template_data();
        return self::$template_datas['footer_id'] ?? 0;
    }
    public static function get_after_footer_id(){
        
        self::conditional_set_template_data();
        return self::$template_datas['after_footer_id'] ?? 0;
    }

    private static function conditional_set_template_data(){
        if( ! self::$template_datas ){
            self::set_template_data();
        }
    }
    private static function set_template_data(){

        /**
         * It will find and set current page type. It's obviously need for here.
         * Otherwise it will unable to set $template_datas
         */
        self::get_current_page_type();
        
        $pagewise_tem = [];
        foreach( self::$heder_footer_data as $key=>$datas ){
            $position = $datas['position'];
            $rules = $datas['rule'] ?? [];
            foreach( $rules as $rule ){
                $pagewise_tem[$position][$rule] = $key;
            }
        }
        self::$pagewise_template = $pagewise_tem;
        
                
        return self::$template_datas = [
            'header_id' => self::get_loc_wise_id( 'header' ),
            'before_header_id' => self::get_loc_wise_id( 'before_header' ),
            'footer_id' => self::get_loc_wise_id( 'footer' ),
            'after_footer_id' => self::get_loc_wise_id( 'after_footer' ),
        ];
    }

    /**
     * Getting Location(header/footer/before_header/after_footer) wise 
     * selected Template ID
     * when page/content will load and specific page.
     * 
     * In this method, we will take help from 
     * Method: self::get_current_page_type();
     * It's compolsury before this function
     * 
     * @param String $location_name it's should be within header/footer/before_header/after_footer etc
     *
     * @return void
     */
    public static function get_loc_wise_id( $location_name = 'header' ){
        if(null == self::$current_page_type){
            self::get_current_page_type();
        }

        $location_s_sett = self::$pagewise_template[$location_name] ?? false;

        if( ! $location_s_sett ) return false;

        $header_id = $location_s_sett['entire_site'] ?? 0;
        if( self::is_woocommerce() ){
            $header_id = $location_s_sett['is_woocommerce'] ?? $header_id;
            $header_id = $location_s_sett['is_wc_taxonomy'] ?? $header_id;
            $header_id = $location_s_sett['is_wc_category'] ?? $header_id;

        }else{
            $header_id = $location_s_sett['is_home'] ?? $header_id;
            $header_id = $location_s_sett['is_tax'] ?? $header_id;
            $header_id = $location_s_sett['is_tax'] ?? $header_id;
        }
        
        $location_id = $location_s_sett[self::$current_page_type] ?? $header_id;

        return $location_id;
    }
    /**
     * Set your template 
     * we can use this method for any kind of template set
     * specially for hader template and footer template
     *
     * @param string $template_name
     * @return void
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @since 1.1.4.5
     */
    public static function set_template( $template_name = 'header' ) {

        /**
         * Actually we already checked based on file found, so no need based on 'header' and 'footer' string
         */
        // if($template_name !== 'header'&& $template_name !== 'footer' ) return;
        
        $file = ULTRA_ADDONS_DIR . 'template/' . $template_name . '.php';
        if( ! is_file( $file ) ) return;
        include $file;
        
        
        // $templates   = [];
        $templates[] = $template_name . '.php';
        
        $rem_action = 'wp_head';
        
        if(strpos('footer',$template_name) !== false){
            $rem_action = 'wp_footer';
            
        }
        // Avoid running wp_head hooks again.
        remove_all_actions( $rem_action );
        ob_start();
        
        locate_template( $templates, true );
        ob_get_clean();
    }

    public static function enqueue_scripts(){
        $handle = 'ultraaddons-icon-font';
        $src = ULTRA_ADDONS_ASSETS . 'css/header-footer.css';
        wp_register_style( $handle, $src );//, $deps, $ver, $media
        wp_enqueue_style( $handle );

        if(!is_array( self::$heder_footer_data )) return;
        foreach(self::$heder_footer_data as $template_id => $templates){
            
            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
                $css_file->enqueue();
            } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                $css_file = new \Elementor\Post_CSS_File( $template_id );
                $css_file->enqueue();
            }
        }
        
        
        
    }

    /**
	 * Get current page type
     * It's not only get actually It's also for set
	 *
	 * @since  1.0.0
	 *
	 * @return string Page Type.
	 */
	public static function get_current_page_type() {
		if ( null === self::$current_page_type ) {
			$page_type  = '';
			$current_id = false;
            // var_dump(is_404(),is_search());
			if ( is_404() ) {
				$page_type = 'is_404';
			} elseif ( is_search() ) {
				$page_type = 'is_search';
                if(self::is_woocommerce()){
                    $page_type  = 'is_wc_search';
                }
			} elseif ( is_archive() ) {
				$page_type = 'is_archive';
                // var_dump(is_category(), is_tag(), is_tax());
				if ( is_category() || is_tag() || is_tax() ) {
					$page_type = 'is_tax';
                    if ( function_exists( 'is_product_category' ) && is_product_category() ) {
                        $page_type = 'is_wc_category';
                    }if ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
                        $page_type = 'is_wc_taxonomy';
                    }
				} elseif ( is_date() ) {
					$page_type = 'is_date';
				} elseif ( is_author() ) {
					$page_type = 'is_author';
				} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
					$page_type = 'is_wc_shop';
				}
			} elseif ( is_home() ) {
				$page_type = 'is_home';
			} elseif ( is_front_page() ) {
				$page_type  = 'is_front_page';
				$current_id = get_the_id();
			} elseif ( is_singular() ) {
				$page_type  = 'is_singular';
				$current_id = get_the_id();
                if ( function_exists( 'is_product' ) && is_product() ) {
					$page_type = 'is_wc_single';
				}elseif(self::is_woocommerce()){
                        $page_type  = 'is_woocommerce';
                }
			} else {
				$current_id = get_the_id();
			}
            
            

			self::$current_page_data['ID'] = $current_id;
			self::$current_page_type       = $page_type;
		}

		return apply_filters('ultraaddons_hf_current_page_type', self::$current_page_type);
	}
    
    private static function is_woocommerce(){
        if(!function_exists('is_woocommerce')) return false;

        if( is_cart() || is_checkout() || is_woocommerce() || is_checkout_pay_page() || is_account_page()){
            return true;
        }

    }
}
