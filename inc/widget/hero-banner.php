<?php
namespace UltraAddons\Widget;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Utils;
use \Elementor\Core\Schemes;
use \ELEMENTOR\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Hero_Banner extends Base{
    
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
        return [ 'ultraaddons','ua', 'hero', 'header', 'banner', 'call to action', 'c2a' ];
    }
    
    // Get Control ID
    protected function get_control_id( $control_id ) {
        return $control_id;
    }
   
    final public function get_banner_settings( $control_key ) {
        $control_id = $this->get_control_id( $control_key );
        return $this->get_settings( $control_id );
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
        $this->preset_controls();
        $this->content_controls();
        $this->general_style_controls();
        $this->images_style_controls();
        $this->title_style_controls();
        $this->content_style_controls();
        $this->button_style_controls();
        $this->button2_style_controls();
       
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
        
        $skin  = !empty( $settings['ua_skin'] ) ? $this->get_banner_settings('ua_skin') : '_skin_1';
        
        switch ($skin) {
            case '_skin_1':
                 $this->layout_one();
                break;
            case '_skin_2':
                 $this->layout_two();
                break;
            case '_skin_3':
                 $this->layout_three();
                break;
            case '_skin_4':
                 $this->layout_four();
                break;
            case '_skin_5':
                $this->layout_five();
                break;
            case '_skin_6':
                $this->layout_six();
                break;
            // case '_skin_7':
            //     $this->layout_seven();
            //     break;
            // case '_skin_8':
            //     $this->layout_eight();
            //     break;
            case '_skin_7': // this one replaced with _skin_9
                $this->layout_nine();
                break;
            // case '_skin_10':
            //     $this->layout_ten();
            //     break;
            default:
                $this->layout_one();
                break;
        } 
        
//        var_dump($ua_skin);
//        var_dump($settings['ua_skin']);
//        var_dump($settings);
    }
    
    
    //Layout One
    protected function layout_one(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );


        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        $button2_icon = $this->get_banner_settings('_ua_banner2_icon');
        ?>
         <div class="ua_banner_section ua_banner_section_style_01">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center <?php echo esc_attr($row_revers); ?>">
                    <div class="ua_col_lg_7"> 
                        <div class="ua_banner_content ua_banner_content">
                            <?php if ( $has_title_text ) : ?> 
                            <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                            </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                            <?php if($has_button_active): ?>
                                <?php if($ua_button_text): ?>  
                                    <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif; ?>
                                <?php if($ua_button2_text): ?>  
                                    <a href="<?php echo esc_url($this->get_banner_settings('_ua_banner2_link')['url']); ?>" class="ua_video_popup_area ua_cu_btn btn_4"> 
                                        <?php Icons_Manager::render_icon( $this->get_banner_settings('_ua_banner2_icon') ); ?>
                                        <?php echo wp_kses_post($this->get_banner_settings('ua_button2_text')); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ( $has_image ) : ?>
                    <div class="ua_banner_img">
                     <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                        <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                     <?php } ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            </div> 
    <?php }

    //Layout Two
    protected function layout_two(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );
        $revers = $this->get_banner_settings('ua_revers');

        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }

        ?>
         
         <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_02 ua_banner_overlay">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center ua_justify_content_between <?php echo esc_attr($row_revers); ?>">
                    <div class="ua_col_lg_7"> 
                        <div class="ua_banner_content ua_banner_content_style_01">
                            <?php if ( $has_title_text ) : ?> 
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            
                            <?php if($has_button_active): ?>
                                
                                <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                                <?php if($ua_button_text): ?>
                                <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif; ?>
                                <?php if($ua_button2_text): ?> 
                                <a href="<?php echo esc_url($this->get_banner_settings('_ua_banner2_link')['url']); ?>" class="ua_video_popup_area ua_cu_btn btn_4"> 
                                <?php Icons_Manager::render_icon( $this->get_banner_settings('_ua_banner2_icon') ); ?>
                                <?php echo wp_kses_post($this->get_banner_settings('ua_button2_text')); ?>
                                </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ( $has_image ) : ?>
                    <div class="ua_col_lg_4">
                        <div class="ua_banner_img ua_img_round_shape">
                            <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                                <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                            <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- banner part end -->
    <?php }

    //Layout Three
    protected function layout_three(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );


        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        ?>
         <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_03 ua_banner_overlay ua_mt_80">
        <div class="ua_container">
            <div class="ua_row ua_align_items_center ua_justify_content_between <?php echo esc_attr($row_revers); ?>">
                <div class="ua_col_lg_7"> 
                    <div class="ua_banner_content ua_banner_content_style_02">
                            <?php if ( $has_title_text ) : ?> 
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>

                            <?php if($has_button_active): ?>
                                <?php if($ua_button_text): ?>
                                <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif; ?>
                                <?php if($ua_button2_text): ?> 
                                <a href="<?php echo esc_url($this->get_banner_settings('_ua_banner2_link')['url']); ?>" class="ua_video_popup_area ua_cu_btn btn_4"> 
                                <?php Icons_Manager::render_icon( $this->get_banner_settings('_ua_banner2_icon') ); ?>
                                <?php echo wp_kses_post($this->get_banner_settings('ua_button2_text')); ?>
                                </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ( $has_image ) : ?>
                    <div class="ua_col_lg_4">
                        <div class="ua_banner_img ua_img_round_shape">
                            <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                                <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                            <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php }

    //Layout One
    protected function layout_four(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );


        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }   
        ?>
         <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_03 ua_banner_overlay ua_mt_80">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center ua_justify_content_between <?php echo esc_attr($row_revers); ?>">
                    <div class="ua_col_xl_7 ua_col_md_6"> 
                            <div class="ua_banner_content ua_banner_content_style_03">
                                <?php if ( $has_title_text ) : ?> 
                                    <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                        <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                    </<?php echo $ua_title_size; ?>>
                                <?php endif; ?>
                                <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                                
                                <?php if($has_button_active): ?>
                                    <?php if($ua_button_text): ?>
                                    <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ( $has_image ) : ?>
                            <div class="ua_col_xl_5 ua_col_md_6">
                                <div class="ua_banner_img">
                                    <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                                        <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                                    <?php } ?>
                                </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    <?php }
   
    //Layout One
    protected function layout_five(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );


        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        ?>
        <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_04">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center <?php echo esc_attr($row_revers); ?>">
                    <div class="ua_col_lg_6"> 
                        <div class="ua_banner_content ua_banner_content_style_04">
                        <?php if ( $has_title_text ) : ?>   
                            <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                            <?php if($has_button_active): ?>
                                <?php if($ua_button_text): ?>
                                    <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ( $has_image ) : ?>
                    <div class="ua_banner_img">
                        <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                            <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                        <?php } ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php }


    protected function layout_six(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );

        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        ?>
        <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_08 ua_mt_80">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center <?php echo esc_attr($row_revers); ?>">
                    <div class="ua_col_xl_5 ua_col_md_4"> 
                        <?php if ( $has_image ) : ?>
                            <div class="ua_banner_img_wrapper">
                            <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                                <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                            <?php } ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="ua_col_xl_7 ua_col_md_8"> 
                        <div class="ua_banner_content ua_banner_content_style_07">
                            <?php if ( $has_title_text ) : ?>
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <form action="#" class="ua_banner_subscribe_form">
                                <input type="email" name="ua_email" id="ua_email1" placeholder="Type your e-mail">
                                <i class="fas fa-envelope ua_mail_icon"></i>
                                <?php if($ua_button_text): ?>
                                    <button type="submit" class="ua_cu_btn"><?php echo $this->get_banner_settings('ua_button_text'); ?></button>
                                <?php endif; ?>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>                        
    <?php }

    protected function layout_seven(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );

        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }

        ?>
        <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_06 ua_mt_80">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center <?php echo esc_attr($row_revers); ?>">
                    <div class="ua_col_lg_6"> 
                        <div class="ua_banner_content ua_banner_content_style_06">
                            <?php if ( $has_title_text ) : ?>
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                            <?php if($has_button_active): ?>
                                <?php if($ua_button_text): ?>
                                <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif; ?>
                                <?php if($ua_button2_text): ?>
                                <a href="<?php echo esc_url($this->get_banner_settings('_ua_banner2_link')['url']); ?>" class="ua_video_popup_area ua_cu_btn btn_1"> 
                                <?php Icons_Manager::render_icon( $this->get_banner_settings('_ua_banner2_icon') ); ?>
                                <?php echo wp_kses_post($this->get_banner_settings('ua_button2_text')); ?>
                                </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ( $has_image ) : ?>
                    <div class="ua_main_banner_img">
                    <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                        <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                    <?php } ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>  
    <?php }

    protected function layout_eight(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );


        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        ?>
        <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_05 ua_mt_80">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center">
                    <div class="ua_col_lg_12"> 
                        <div class="ua_banner_content ua_banner_content_style_05">
                            <?php if ( $has_title_text ) : ?>
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo esc_html( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                        </div>
                    </div>
                    <?php if ( $has_image ) : ?>
                    <div class="ua_main_banner_img">
                    <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                        <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                    <?php } ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php }

    protected function layout_nine(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );

        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        ?>
        <!-- banner part -->
        <div class="ua_banner_section ua_banner_section_style_02 ua_banner_overlay ua_overlay_opacity">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center ua_justify_content_center">
                    <div class="ua_col_lg_12"> 
                        <div class="ua_banner_content ua_banner_content_style_09 ua_text_center">
                            <?php if ( $has_title_text ) : ?>
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo wp_kses_post( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                            <?php if($has_button_active): ?>
                                <?php if($ua_button_text): ?>
                                    <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_2"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif;  ?>
                            <?php endif;  ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }

    protected function layout_ten(){
        $settings = $this->get_settings_for_display();
        $has_image = ! empty( $this->get_banner_settings('ua_images_show') );
        $has_title_text = ! empty( $this->get_banner_settings('ua_title') );
        $description_text = $this->get_banner_settings('ua_description_text');
        $ua_title_size =  $this->get_banner_settings('ua_title_size');
        $has_button_active = ! empty( $this->get_banner_settings('ua_button_show') );
        $ua_button_text = ! empty( $this->get_banner_settings('ua_button_text') );
        $ua_button2_text = ! empty( $this->get_banner_settings('ua_button2_text') );

        $revers = $this->get_banner_settings('ua_revers');
        $row_revers = '';
        if($revers =='yes'){
            $row_revers = 'ua-row-revers';
        }
        ?>
        <!-- banner part -->    
        <div class="ua_banner_section ua_banner_section_style_09">
            <div class="ua_container">
                <div class="ua_row ua_align_items_center">
                    <div class="ua_col_lg_6"> 
                        <div class="ua_banner_content ua_banner_content_style_10">
                        <?php if ( $has_title_text ) : ?>
                                <<?php echo $ua_title_size; ?> class="ua_banner_title">
                                    <?php echo wp_kses_post( $this->get_banner_settings('ua_title') ); ?>
                                </<?php echo $ua_title_size; ?>>
                            <?php endif; ?>
                            <p class="ua_banner_desc"><?php echo wp_kses_post( $this->get_banner_settings('ua_description_text') ); ?></p>
                            <?php if($has_button_active): ?>
                                <?php if($ua_button_text): ?>
                                    <a href="<?php echo esc_url($this->get_banner_settings('ua_button_link')['url']); ?>" class="ua_cu_btn btn_3"><?php echo $this->get_banner_settings('ua_button_text'); ?></a>
                                <?php endif;  ?>
                            <?php endif;  ?>
                        </div>
                    </div>
                </div>
            </div>
        

            <?php if ( $has_image ) : ?>
                <div class="ua_main_banner_img">
                <?php  if ( ! empty( $this->get_banner_settings('ua_images_feature')['url'] ) ) {  ?>
                    <img src="<?php echo esc_url($this->get_banner_settings('ua_images_feature')['url']); ?>" alt="#" class="ua_img_res">
                <?php } ?>
                </div>
            <?php endif; ?>
        </div>

    <?php }
    
    
      //Preset
   public function preset_controls() {
		$this->start_controls_section(
			'ua_preset_section',
			[
				'label' => __( 'Preset', 'ultraaddons' ),
			]
        );
        $this->add_control(
			'ua_skin',
			[
				'label' => esc_html__( 'Design Format', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options'   => [
					'_skin_1' => 'Style 01',
					'_skin_2' => 'Style 02',
                    '_skin_3' => 'Style 03',
					'_skin_4' => 'Style 04',
					'_skin_5' => 'Style 05',
					'_skin_6' => 'Style 06',
					// '_skin_7' => 'Style 07',
					// '_skin_8' => 'Style 08',
					'_skin_7' => 'Style 07',
					// '_skin_10' => 'Style 10',
				],
				'default' => '_skin_1'
			]
		);
		$this->add_control(
			'ua_revers',
			[
				'label' => __( 'Banner Reverse', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					$this->get_control_id( 'ua_skin' ) => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_4','_skin_5', '_skin_6','_skin_7'],
                ],
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'NO', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'No',
			]
		);
        
		$this->end_controls_section();
	}

	//Content
   public function content_controls(){
		$this->start_controls_section(
			'ua_content_section',
			[
				'label' => __( 'Content', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'ua_title',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'The quickest & easiest service provider', 'ultraaddons' ),
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'label_block' => true,
			]
		);
		
        
        $this->add_control(
            'ua_title_size',
            [
                'label' => __( 'Title HTML Tag', 'ultraaddons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'ultraaddons' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'p'  => [
                        'title' => __( 'P', 'ultraaddons' ),
                        'icon' => 'eicon-editor-paragraph'
                    ],
                ],
                'default' => 'h4',
                'toggle' => false,
                
            ]
        );
		
		$this->add_control(
			'ua_description_text',
			[
				'label' => 'Description',
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Easily and reliably host a website for your business, organization, or project.', 'ultraaddons' ),
				'placeholder' => __( 'Enter your description', 'ultraaddons' ),
				'show_label' => true,
                'rows' => 10,
			]
		);
        
        
        $this->add_control(
            'ua_images_show',
            [
                'label' => esc_html__('Enable Images', 'ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_images_feature', [
                'label'      => __('Feature Image', 'ultraaddons'),
                'type'       => \Elementor\Controls_Manager::MEDIA,
                'default'    => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'show_label' => true,
                'condition' => [
					$this->get_control_id('ua_images_show') => ['yes'],
                ]
            ]
        );
        $this->end_controls_section();

         //Content
         
        $this->start_controls_section(
            'ua_button_section',
            [
                'label' => __( 'Banner Button', 'ultraaddons' ),
            ]
        );

        $this->add_control(
            'ua_button_show',
            [
                'label' => esc_html__('Enable Button', 'ultraaddons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );
		$this->add_control(
			'ua_button_text',
			[
				'label' => __( 'Button', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Work With Us', 'ultraaddons' ),
				'placeholder' => __( 'Enter your text', 'ultraaddons' ),
				'label_block' => true,
                'condition' => [
                    $this->get_control_id( 'ua_button_show' ) => [ 'yes' ],
                ],
			]
		);
		$this->add_control(
			'ua_button_link',
			[
				'label' => __( 'Button Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                'default'   => [
                    'url' => '#'
                ],
                'condition' => [
                    $this->get_control_id( 'ua_button_show' ) => [ 'yes' ],
                ],
			]
		);
		
        
        $this->add_control(
			'ua_button2_text',
			[
				'label' => __( 'Button Two', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Watch Video', 'ultraaddons' ),
				'placeholder' => __( 'Enter your text', 'ultraaddons' ),
				'label_block' => true,
                'condition' => [
					$this->get_control_id( 'ua_button_show' ) => [ 'yes' ],
					$this->get_control_id( 'ua_skin' ) => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_7'],
                ],
			]
		);
		$this->add_control(
			'_ua_banner2_link',
			[
				'label' => __( 'Button Two Link', 'ultraaddons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                'default'   => [
                    'url' => '#'
                ],
                'condition' => [
					$this->get_control_id( 'ua_button_show' ) => [ 'yes' ],
					$this->get_control_id( 'ua_skin' ) => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_7'],
                ],
			]
		);

		$this->add_control(
			'_ua_banner2_icon',
			[
				'label' => __( 'Button Two Icon', 'ultraaddons' ),
				'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-play',
                    'library' => 'fa-solid',
                ],
			]
		);
        $this->end_controls_section();

	}

	//General
	public function general_style_controls(){
		$this->start_controls_section(
            'ua_style_general',
            [
                'label' => esc_html__('General', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bn_background',
                'label' => esc_html__('Background Color', 'ultraaddons'),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .ua_banner_section_style_01, {{WRAPPER}} .ua_banner_section_style_02, {{WRAPPER}} .ua_banner_section_style_09',
            ]
        );
		
        $this->end_controls_section();
	}

	//Banner Images Setting
	public function images_style_controls(){
		$this->start_controls_section(
            'ua_style_images',
            [
                'label' => esc_html__('Images', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_responsive_control(
            'image_space_first',
            [
                'label' => __( 'Spacing', 'ultraaddons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ua_banner_img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua_banner_img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ua_banner_img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .ua_banner_img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size_width_first',
            [
                'label'      => __('Width', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%'],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%'   => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],

                'selectors'  => [
                    '{{WRAPPER}} .ua_banner_img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_size_height_first',
            [
                'label'      => __('Height', 'ultraaddons'),
                'type'       => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '',
                ],
                'size_units' => ['px', '%', 'em', 'rem'],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%'   => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],

                'selectors'  => [
                    '{{WRAPPER}} .ua_banner_img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding_first',
            [
                'label' => esc_html__('Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ultraaddons-card-box-wrapper .ua_banner_img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();
	}


	//banner Title Style
	public function title_style_controls() {
		$this->start_controls_section(
            'ua_title_style_settings',
            [
                'label' => esc_html__('Title', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_title_typography',
                'selector' => '{{WRAPPER}} .ua_banner_content .ua_banner_title',
            ]
        );
        $this->add_control(
            'ua_text_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_banner_content .ua_banner_title' => 'color: {{VALUE}};',
                ],
            ]
		);
		
		$this->add_responsive_control(
			'ua_title_bottom_space',
			[
				'label' => __( 'Spacing', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua_banner_content .ua_banner_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();
	}

	//Content Style
	public function content_style_controls(){
		$this->start_controls_section(
            'ua_content_style_settings',
            [
                'label' => esc_html__('Content', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ua_content_typography',
                'selector' => '{{WRAPPER}} .ua_banner_content .ua_banner_desc',
            ]
        );
        $this->add_control(
            'ua_content_color',
            [
                'label' => esc_html__('Text Color', 'ultraaddons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ua_banner_content .ua_banner_desc' => 'color: {{VALUE}};',
                ],
            ]
		);
		$this->add_responsive_control(
			'ua_content_bottom_space',
			[
				'label' => __( 'Spacing', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua_banner_content .ua_banner_desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'content_padding_first',
            [
                'label' => esc_html__('Content Padding', 'ultraaddons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ua_banner_content.ua_banner_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    

    //Button Style
	public function button_style_controls(){
		$this->start_controls_section(
            'ua_button_style_settings',
            [
                'label' => esc_html__('Button One', 'ultraaddons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( 'button_effects' );

		$this->start_controls_tab( '_button_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .btn_2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua_cu_btn.btn_3' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_3' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .btn_2' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .ua_banner_content .btn_3',
			]
		);
		$this->add_control(
			'btn_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ua_banner_content .btn_3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .btn_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua_banner_content .btn_3, {{WRAPPER}} .btn_2, {{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( '_button_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_3:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn_2:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_3:hover, {{WRAPPER}} .btn_2:hover, {{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'hover_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .btn_2:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua_cu_btn.btn_3:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_hover_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_3:hover, {{WRAPPER}} .ua_cu_btn.btn_3:hover' => 'transition-duration: {{SIZE}}s',
					'{{WRAPPER}} .btn_2:hover, {{WRAPPER}} .btn_2:hover' => 'transition-duration: {{SIZE}}s',
					'{{WRAPPER}} .ua_banner_content .ua_banner_subscribe_form .ua_cu_btn:hover, {{WRAPPER}} .ua_cu_btn.btn_3:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
        
        $this->end_controls_section();
    }
    
     //Button Two Style
     public function button2_style_controls(){
		$this->start_controls_section(
            'ua_button2_style_settings',
            [
				'label' => esc_html__('Button Two', 'ultraaddons'),
				'condition' => [
					$this->get_control_id('ua_skin') => ['_skin_1', '_skin_2', '_skin_3' ,'_skin_7'],
				],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( 'button2_effects' );

		$this->start_controls_tab( '_button2_normal',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
		
		$this->add_control(
			'button2_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua_cu_btn.btn_1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button2_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_4' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .ua_cu_btn.btn_1' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button2_typography',
				'selector' => '{{WRAPPER}} .ua_banner_content .btn_4, {{WRAPPER}} .ua_cu_btn.btn_1',
				
			]
		);
		$this->add_control(
			'btn2_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ua_banner_content .btn_4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua_cu_btn.btn_1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button2_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua_banner_content .btn_4' => 'transition-duration: {{SIZE}}s',
					'{{WRAPPER}} .ua_cu_btn.btn_1' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( '_button2_hover',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);

		$this->add_control(
			'button2_hover_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_4:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ua_cu_btn.btn_1:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button2_hover_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_4:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .ua_cu_btn.btn_1:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button2_hover_border_radius',
			[
				'label' => __( 'Border Radius', 'ultraaddons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_4:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ua_cu_btn.btn_1:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button2_hover_transition',
			[
				'label' => __( 'Transition Duration', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua_cu_btn.btn_4:hover, {{WRAPPER}} .ua_cu_btn.btn_4:hover, {{WRAPPER}} .ua_cu_btn.btn_1:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
        
        $this->end_controls_section();
    }
}
