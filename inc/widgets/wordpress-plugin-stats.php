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

class WordPress_Plugin_Stats extends Base{
    
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
        return [ 'ultraaddons', 'stats', 'wp', 'plugin', 'wordpress' ];
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
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        /**
         * Active Installation:
         * https://api.wordpress.org/stats/plugin/1.0/active-installs.php?slug={PLUGINSLUG}&limit=728
         */
        
        $settings           = $this->get_settings_for_display();

        var_dump($settings['plugin_slug']);
        $slug = 'ultraaddons-elementor-lite';
        
        $plugin_slug = 'wc-quantity-plus-minus-button'; 
        //Transient name with plugin's slug, so that, if a user if change plugin, than data will be update/change
        $transient_name = 'ua_stats-' . $plugin_slug;
        $transient = get_transient( $transient_name );

        if( ! $transient ){
            //woo-product-table //ultraaddons-elementor-lite
            $info_url = "https://api.wordpress.org/plugins/info/1.0/{$plugin_slug}.json?fields=banners,icons,active_installs";
            $str = file_get_contents( $info_url, false );
            $transient = json_decode($str, true); // decode the JSON into an associative array
            $expire = apply_filters( 'ultraaddons_wp_plugin_stats_expire', 21600 ); 
            set_transient( $transient_name, $transient, $expire );
        }
        
        $downloaded = ! empty( $transient['downloaded'] ) ? $transient['downloaded'] : false;
        $downloaded_label = "Download";
        
        $active_installs = ! empty( $transient['active_installs'] ) ? $transient['active_installs'] : false;
        $active_installs_label = "Active Install";
        $version = ! empty( $transient['version'] ) ? $transient['version'] : false;
        $rating = ! empty( $transient['rating'] ) ? $transient['rating'] : 100;
        
        $final_rating = ( $rating / 100 ) * 5;
        $final_rating_label = 'Rating';
        
        ?>
<div class="wp-plugins-stats-wrapper">
    <div class="wp-plugin-stats">
        <div class="plugin-stats plugin-stats-downloaded">
            <span class="download-number"><?php echo esc_html( $downloaded ); ?><b>+</b></span>
            <span class="download-label"><?php echo esc_html( $downloaded_label ); ?></span>
        </div>
        
        <div class="plugin-stats plugin-stats-active-install">
            <span class="active-number"><?php echo esc_html( $active_installs ); ?><b>+</b></span>
            <span class="active-label"><?php echo esc_html( $active_installs_label ); ?></span>
        </div>
        
        <div class="plugin-stats plugin-stats-rating">
            <span class="rating-number"><?php echo esc_html( $final_rating ); ?></span>
            <span class="rating-label"><?php echo esc_html( $final_rating_label ); ?></span>
        </div>
        
        
    </div>
</div>
        <?php
    }
    
    /**
     * General Section
     * such:
     * plugin Slug,
     * Plugin Name (Optional)
     * 
     * @since 1.0.7.19
     */
    protected function content_general_controls() {
        $this->start_controls_section(
            'general',
            [
                'label'     => esc_html__( 'General', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'plugin_slug',
                [
                    'label'         => esc_html__( 'Heading', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __( 'Your plugin slug. eg: ultraaddons-elementor-lite', 'ultraaddons' ),
                    'default'       => 'ultraaddons-elementor-lite',
                    'description'   => 'Only input WordPress.org plugin slug, not full url of plugin.',
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'plugin_name',
                [
                    'label'         => esc_html__( 'Plugin Name (optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __( 'Plugin display name. eg: UltraAddons Elementor Lite', 'ultraaddons' ),
                    'default'       => '',
                    'label_block'   => TRUE,
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->end_controls_section();
    }

}
