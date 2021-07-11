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
               
        //For Design Section Style Tab
        $this->style_design_controls();
        
        
        //Typography
        $this->style_typography_controls();
        
        
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

        
        $slug = $settings['plugin_slug'];
        $plugin_name = $settings['plugin_name'];
        
        $plugin_slug = ! empty( $slug ) ? $slug : 'ultraaddons-elementor-lite'; 
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
        
        //Name 
        $plugin_name = ! empty( $plugin_name ) ? $plugin_name : $transient['name'];
        
        $downloaded = ! empty( $transient['downloaded'] ) ? $transient['downloaded'] : 0;
        $downloaded_label = ! empty( $settings['download_text'] ) ? $settings['download_text'] : "Download";
        
        $active_installs = ! empty( $transient['active_installs'] ) ? $transient['active_installs'] : 0;
        $active_installs_label = ! empty( $settings['active_install_text'] ) ? $settings['active_install_text'] : "Active Install";
        $version = ! empty( $transient['version'] ) ? $transient['version'] : false;
        $rating = ! empty( $transient['rating'] ) ? $transient['rating'] : 100;
        $final_rating = ( $rating / 100 ) * 5;
        $final_rating_label = ! empty( $settings['rating_text'] ) ? $settings['rating_text'] : 'Rating';
        
        ?>
<div class="wp-plugins-stats-wrapper">
    <div class="wp-plugin-name">
        <h3 class="wp-plugin-name-heading"><?php echo esc_html( $plugin_name ); ?></h3>
    </div>
    <div class="wp-plugin-stats">
        <div class="plugin-stats plugin-stats-downloaded">
            <span class="download-number number-text"><?php echo esc_html( $downloaded ); ?><b>+</b></span>
            <span class="download-label label-text"><?php echo esc_html( $downloaded_label ); ?></span>
        </div>
        
        <div class="plugin-stats plugin-stats-active-install">
            <span class="active-number number-text"><?php echo esc_html( $active_installs ); ?><b>+</b></span>
            <span class="active-label label-text"><?php echo esc_html( $active_installs_label ); ?></span>
        </div>
        
        <div class="plugin-stats plugin-stats-rating">
            <div class="rating-number number-text">
				<div class="star-rating" title="Rated <?php echo esc_html( $final_rating ); ?> out of 5">
					<span style="width:<?php echo esc_attr( ( $final_rating * 100 ) / 5 ); ?>%">
					<strong class="rating"><?php echo esc_html( $final_rating ); ?></strong> out of <span>5</span> based on <span class="rating"><?php echo esc_html( $transient['rating'] );?></span> customer ratings </span>
				</div>
            </div>
            <span class="rating-label label-text"><?php echo esc_html( $final_rating_label ); ?></span>
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
                    'separator'     => 'before',
                ]
        );
        
        $this->add_control(
            'download_text',
                [
                    'label'         => esc_html__( 'Download Text (optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,  
                    'default'       => 'Download',
                    'dynamic'       => ['active' => true],
                ]
        );
        
        $this->add_control(
            'active_install_text',
                [
                    'label'         => esc_html__( 'Active Install Text (optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,  
                    'default'       => 'Active Install',
                    'dynamic'       => ['active' => true],
                ]
        );
        $this->add_control(
            'rating_text',
                [
                    'label'         => esc_html__( 'Rating Text (optional)', 'ultraaddons' ),
                    'type'          => Controls_Manager::TEXT,  
                    'default'       => 'Rating',
                    'dynamic'       => ['active' => true],
                ]
        );
        
        
        $this->end_controls_section();
    }

    /**
     * Alignment Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_design_controls() {
        $this->start_controls_section(
            'style',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        
        $this->add_control(
            'heading_color',
            [
                'label'     => __( 'Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-plugin-name-heading' => 'color: {{VALUE}}',
                ],
                'default'   => '#0fc392',
            ]
        );
        
        $this->add_control(
            'label_text_color',
            [
                'label'     => __( 'Label Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-plugin-stats .label-text' => 'color: {{VALUE}}',
                ],
                'default'   => '#8D93A3;
',
            ]
        );
        
        $this->add_control(
            'number_text_color',
            [
                'label'     => __( 'Number Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-plugin-stats .number-text' => 'color: {{VALUE}}',
                ],
                'default'   => '#5956E9',
            ]
        );
        
        $this->add_responsive_control(
                'heading_spacing',
                [
                        'label' => __( 'Heading Spacing', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                                'size' => 10,
                        ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 100,
                                ],
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .wp-plugin-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );

        $this->end_controls_section();
    }
    
    /**
     * Typography Section for Style Tab
     * 
     * @since 1.0.0.9
     */
    protected function style_typography_controls() {
        $this->start_controls_section(
            'typography',
            [
                'label'     => esc_html__( 'Typography', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'title_typography',
                        'label' => 'Heading Typography',
                        'selector' => '{{WRAPPER}} .wp-plugin-name-heading',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'label_typography',
                        'label' => 'Label Typography',
                        'selector' => '{{WRAPPER}} .wp-plugin-stats .label-text',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'number_typography',
                        'label' => 'Number Typography',
                        'selector' => '{{WRAPPER}} .wp-plugin-stats .number-text',
                        'global' => [
                                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                        ],

                ]
        );
        
        
        $this->end_controls_section();
    }
    
}
