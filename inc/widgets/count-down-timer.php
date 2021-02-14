<?php
namespace UltraAddons\Widget; 

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Count_Down_Timer extends Base{
    
    
    /**
     * Widget Icon.
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-countdown';
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
        return [ 'ultraaddons', 'timer', 'count', 'down', 'countdown', 'count down timer', 'count timer','clock','watch' ];
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
        //$this->style_design_controls();
        
        //For Typography Section Style Tab
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
        $settings           = $this->get_settings_for_display();
        $unique_class = 'ua-count-down-' . rand( 509,1254 );
        $this->add_render_attribute( 'wrapper', 'class', 'ua-coun-down-timer-wrapper' );
        //$this->add_render_attribute( 'wrapper', 'class', $unique_class );
        
//        var_dump($settings['date_time']);
        $date = $settings['date_time'];
        $date_time = date( 'm/d/Y H:i', strtotime($date) );
        //$items = $settings['list_items'];
//echo '<pre>';
//print_r( $settings );
//echo '</pre>';
        //var_dump($settings);
        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        
        <div class="ua-coun-down-timer <?php echo esc_attr( $unique_class ); ?>">
            <div class="single-date"><span class="timer_int days">00 </span><span class="timer_label">Days</span></div>
          :
          <div class="single-date"><span class="timer_int hrs">00 </span><span class="timer_label">Hours</span></div>
          :
          <div class="single-date"><span class="timer_int mnts">00 </span><span class="timer_label">Minutes</span></div>
          :
          <div class="single-date"><span class="timer_int secs">00 </span><span class="timer_label">Seconds</span></div>
        </div>
    </div>
<script type="text/javascript">
    
    // Countdown for page 4
    function getTimeRemaining(endtime) {
        if(Date.parse(endtime) < Date.parse(new Date())){
            return;
        }
        const total = Date.parse(endtime) - Date.parse(new Date());
        const seconds = Math.floor((total / 1000) % 60);
        const minutes = Math.floor((total / 1000 / 60) % 60);
        const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
        const days = Math.floor(total / (1000 * 60 * 60 * 24));

        return {
            total,
            days,
            hours,
            minutes,
            seconds
        };
    }

    function initializeClock(id, endtime) {
        try{
//            const clock = document.getElementById('countdownsss');
            var clock = document.querySelector('.<?php echo esc_attr( $unique_class ); ?>');
            var daysSpan = clock.querySelector('.days');
            var hoursSpan = clock.querySelector('.hrs');
            var minutesSpan = clock.querySelector('.mnts');
            var secondsSpan = clock.querySelector('.secs');

            function updateClock() {
                var t = getTimeRemaining(endtime);

                daysSpan.innerHTML = t.days;
                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }
            }

            updateClock();
            var timeinterval = setInterval(updateClock, 1000);
        }catch(e){
            //e.getMessage();
            return;
        }
    }

    var deadline = new Date(Date.parse('<?php echo esc_attr( $settings['date_time'] ); ?>'));// new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
//    const deadline = new Date(Date.parse('1/25/2021 10:20:00'));// new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
//    console.log(deadline,new Date(Date.parse('1/25/2021 10:20:00')));
    initializeClock('countdown', deadline);
    </script>
          
        <?php
        
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
        
        $default_date_time = date( 'Y-m-d H:i', ( time() + ( 86400 * 16 ) ) );
        $this->add_control(
            'date_time',
            [
                'label'                 => __( 'End Date', 'ultraaddons' ),
                'type'                  => Controls_Manager::DATE_TIME,
                //'default'               => 'YYYY-mm-dd HH:ii',
                ##'default'               => 'mm/dd/YYYY HH:ii',
                'default'               => $default_date_time,//'2021-01-25 12:00',
                'frontend_available'    => true,
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
            'style_general',
            [
                'label'     => esc_html__( 'Design', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'template',
                [
                    'label'         => esc_html__( 'Template', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            'default'   => __( 'Default', 'ultraaddons' ),
                            'temp-2'    => __( 'Template Two', 'ultraaddons' ),
                            'temp-3'    => __( 'Template Three', 'ultraaddons' ),
                    ],
                    'default' => 'default',
                    'prefix_class' => 'ua-list-temp-',
                ]
        );
        
        $this->add_responsive_control(
            'list-column',
                [
                    'label'         => esc_html__( 'Column', 'ultraaddons' ),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                            '100%'     => __( 'One Column', 'ultraaddons' ),
                            '48%'     => __( 'Two Column', 'ultraaddons' ),
                            '30.33%'     => __( 'Three Column', 'ultraaddons' ),
                            '23%'     => __( 'Four Column', 'ultraaddons' ),
                    ],
                    'default' => '30.33%',
                    'prefix_class' => 'ua-list-',
                    'selectors' => [
                                        '{{WRAPPER}} .ua-list-items li' => 'width: {{VALUE}};',
                                ],
                ]
        );
        
        
        
        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Title Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items li .list-item-title' => 'color: {{VALUE}}',
                ],
                'default'   => '#21272c',
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label'     => __( 'Description Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-list-item-wrapper .ua-list-items li .list-item-description' => 'color: {{VALUE}}',
                ],
                'default'   => '#5C6B79',
            ]
        );
        
                
        $this->add_responsive_control(
                'padding',
                [
                        'label' => __( 'Padding', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'size' => 55,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-list-items li.list-item .list-item-inside' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'margin',
                [
                        'label' => __( 'Margin', 'ultraaddons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'default'   => [
                                'size' => 55,
                                'unit' => 'px',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .ua-list-items li.list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        'name' => 'int_typography',
                        'label' => 'Number Typography',
                        'selector' => '{{WRAPPER}} .ua-coun-down-timer .timer_int',
//                        'global' => [
//                                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
//                        ],

                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'label_typography',
                        'label' => 'Label Typography',
                        'selector' => '{{WRAPPER}} .ua-coun-down-timer .timer_label',
//                        'global' => [
//                                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
//                        ],

                ]
        );
        
        
        $this->end_controls_section();
    }
       
    
}