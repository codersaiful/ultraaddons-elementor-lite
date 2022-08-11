<?php
namespace UltraAddons\Classes;

use UltraAddons\Core\Header_Footer;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Header_Footer_Render {
    public static $heder_footer_data = [];
    public static $template_loc = [];
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
        if( ! is_array( $heder_footer_data ) ) return;
        self::$heder_footer_data = $heder_footer_data;
        $locs =[]; // $wasys = 
        
        foreach(self::$heder_footer_data as $key=>$eack ){
            $position = $eack['position'];
            // $way = isset($eack['way']) ? 'css' : 'direct';
            // $wasys[$key]=$way;
            $locs[$position]= $key;
        }

        if(empty($locs)) return;
        // var_dump($locs);
        // var_dump($wasys,$locs,self::$heder_footer_data);
        // var_dump(self::$heder_footer_data);
        
        add_action( 'get_header', [__CLASS__, 'get_header'], 10, 2 );

        // add_action( 'wp_body_open', [__CLASS__, 'add_header'] );
        // add_filter( 'body_class', [__CLASS__, 'header_css_body_class'] );

        add_action( 'get_footer', [__CLASS__, 'get_footer'], 10, 2 );

        // add_action( 'wp_footer', [__CLASS__, 'add_footer'] );
        // add_filter( 'body_class', [__CLASS__, 'header_css_body_class'] );


        //Totally complete, I added all templates css file
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

    public static function add_header(){
        
        echo ultraaddons_elementor_display_content( self::get_header_id() );
    }
    public static function header_css_body_class( $body_class ){
        $body_class[] = 'ua-header-type-css';
        return $body_class;
    }

    public static function add_footer(){
        
        echo ultraaddons_elementor_display_content( self::get_header_id() );
    }
    public static function footer_css_body_class( $body_class ){
        $body_class[] = 'ua-footer-type-css';
        return $body_class;
    }

    public static function get_header($name, $args){

        if( ! self::$template_datas ){
            self::set_template_data();
        }
        // var_dump(self::get_header_id());
        if(self::get_header_id()){
            self::set_template('header');
        }
        
    }
    public static function get_footer($name, $args){

        if( ! self::$template_datas ){
            self::set_template_data();
        }
        // var_dump(self::get_header_id());
        if(self::get_footer_id()){
            self::set_template('footer');
        }
        
    }

    public static function get_header_id(){
        if( ! self::$template_datas ){
            self::set_template_data();
        }
        
        return self::$template_datas['header_id'] ?? 0;
    }

    public static function get_footer_id(){
        if( ! self::$template_datas ){
            self::set_template_data();
        }
        
        return self::$template_datas['footer_id'] ?? 0;
    }
    private static function set_template_data(){
        
        // var_dump($page_type,self::$current_page_data,self::$heder_footer_data);
        $pagewise_tem = [];
        foreach( self::$heder_footer_data as $key=>$datas ){
            $position = $datas['position'];
            $rules = $datas['rule'] ?? [];
            foreach( $rules as $rule ){
                $pagewise_tem[$position][$rule] = $key;
            }
        }

        $page_type = self::get_current_page_type();
        // var_dump($page_type);
        $header = $pagewise_tem['header'] ?? [];
        $entire_header_id = $header['entire_site'] ?? 0;
        $header_id = $header[$page_type] ?? $entire_header_id;

        
        $footer = $pagewise_tem['footer'] ?? [];
        $entire_footer_id = $footer['entire_site'] ?? 0;
        $footer_id = $footer[$page_type] ?? $entire_footer_id;

        
        return self::$template_datas = [
            'header_id' => $header_id,
            'footer_id' => $footer_id,
        ];
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
