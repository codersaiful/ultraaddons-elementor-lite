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
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Countdown_Timer extends Base{
    
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
        return [ 'ultraaddons', 'ua','timer', 'count', 'down', 'countdown', 'count down timer', 'count timer','clock','watch' ];
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
        $this->content_label();
        //For Design Section Style Tab
        $this->style_design_controls();
        
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
        $settings = $this->get_settings_for_display();
        $unique_class = 'ua-count-down-' . rand( 509,1254 );
        $this->add_render_attribute( 'wrapper', 'class', 'ua-coun-down-timer-wrapper' );
        $date = $settings['date_time'];
        $date_time = date( 'm/d/Y H:i', strtotime($date) );
        
        //Label's 
        $days = $settings['days'];
        $hours = $settings['hours'];
        $minutes = $settings['minutes'];
        $seconds = $settings['seconds'];
        
        /**
         * Filter for Changing Date and time.
         * 
         * @since 1.0.0.9
         * @date 16.2.21 d.m.y
         * @author Saiful
         */
        $date_time = apply_filters( 'ultraaddons/widget/count-down-timer/date_time', $date_time, $this->get_name(), $this->get_id(), $this );

        $separator = isset( $settings['show_separator'] ) && $settings['show_separator'] == 'yes' ? '<div class="sep"><span>:</span></div>' : '';
        ?>
    <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
        
        <div class="ua-coun-down-timer <?php echo esc_attr( $unique_class ); ?>">
            <div class="single-date">
                <span class="timer_int days">00 </span>
                <span class="timer_label"><?php echo $days; ?></span>
            </div>
            <?php echo $separator; ?>
            <div class="single-date">
                <span class="timer_int hrs">00 </span>
                <span class="timer_label"><?php echo esc_html( $hours ); ?></span>
            </div>
            <?php echo $separator; ?>
            <div class="single-date">
                <span class="timer_int mnts">00 </span>
                <span class="timer_label"><?php echo esc_html( $minutes ); ?></span>
            </div>
            <?php echo $separator; ?>
            <div class="single-date">
                <span class="timer_int secs">00 </span>
                <span class="timer_label"><?php echo esc_html( $seconds ); ?></span>
            </div>
        </div>
    </div>
<script type="text/javascript">
    
    // Countdown for page 4
    function getTimeRemaining(endtime) {

        var current_time = Date.parse(new Date());
        var finis_time = Date.parse(endtime);

        if(finis_time < current_time){
            return;
        }

        var total = finis_time - current_time;
        var seconds = Math.floor((total / 1000) % 60);
        var minutes = Math.floor((total / 1000 / 60) % 60);
        var hours = Math.floor((total / (1000 * 60 * 60)) % 24);
        var days = Math.floor(total / (1000 * 60 * 60 * 24));

        return {
            total,
            days,
            hours,
            minutes,
            seconds
        };
    }

    function initializeClock(endtime) {
        try{

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

    var deadline = new Date(Date.parse('<?php echo esc_attr( $date_time ); ?>'));
    initializeClock(deadline);
    </script>
          
        <?php
        
    }
    
    protected function content_label(){
        $this->start_controls_section(
            'label',
            [
                'label'     => esc_html__( 'Label', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        
        $this->add_control(
            'days',
            [
                'label'                 => __( 'Day', 'ultraaddons' ),
                'type'                  => Controls_Manager::TEXT,
                'placeholder'   => __( 'eg: Days', 'ultraaddons' ),
                'label_block'   => TRUE,
                'dynamic'       => ['active' => true],
                'default'       => esc_html__( 'Days', 'ultraaddons' ),
            ]
        );
        
        
        $this->add_control(
            'hours',
            [
                'label'                 => __( 'Hour', 'ultraaddons' ),
                'type'                  => Controls_Manager::TEXT,
                'placeholder'   => __( 'eg: Hours', 'ultraaddons' ),
                'label_block'   => TRUE,
                'dynamic'       => ['active' => true],
                'default'       => esc_html__( 'Hours', 'ultraaddons' ),
            ]
        );
        
        
        $this->add_control(
            'minutes',
            [
                'label'                 => __( 'Minute', 'ultraaddons' ),
                'type'                  => Controls_Manager::TEXT,
                'placeholder'   => __( 'eg: Minutes', 'ultraaddons' ),
                'label_block'   => TRUE,
                'dynamic'       => ['active' => true],
                'default'       => esc_html__( 'Minutes', 'ultraaddons' ),
            ]
        );
        
        
        $this->add_control(
            'seconds',
            [
                'label'                 => __( 'Second', 'ultraaddons' ),
                'type'                  => Controls_Manager::TEXT,
                'placeholder'   => __( 'eg: Seconds', 'ultraaddons' ),
                'label_block'   => TRUE,
                'dynamic'       => ['active' => true],
                'default'       => esc_html__( 'Seconds', 'ultraaddons' ),
            ]
        );
        
               
        
        $this->end_controls_section();
        
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
            'date_time',
            [
                'label'                 => __( 'End Date', 'ultraaddons' ),
                'type'                  => Controls_Manager::DATE_TIME,
            ]
        );
        
               
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'general_timer_controls',
            [
                'label'     => esc_html__( 'Timer Controls', 'ultraaddons' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
                'view',
                [
                        'label' => __( 'View', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                'default' => __( 'Default', 'ultraaddons' ),
                                'stacked' => __( 'Stacked', 'ultraaddons' ),
                                'framed' => __( 'Framed', 'ultraaddons' ),
                        ],
                        'default' => 'framed',
                        'prefix_class' => 'elementor-view-',
                ]
        );
        
        $this->add_control(
                'shape',
                [
                        'label' => __( 'Shape', 'ultraaddons' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                                'circle' => __( 'Circle', 'ultraaddons' ),
                                'square' => __( 'Square', 'ultraaddons' ),
                        ],
                        'default' => 'circle',
                        'condition' => [
                                'view!' => 'default',
                        ],
                        'prefix_class' => 'elementor-shape-',
                ]
        );
        
        $this->add_control(
                'show_separator',
                [
                        'label' => __( 'Show Separator?', 'ultraaddons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'ultraaddons' ),
                        'label_off' => __( 'Hide', 'ultraaddons' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                ]
        );
        
        $this->add_responsive_control(
                'box_size',
                [
                        'label' => __( 'Box Size', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                                'px' => [
                                        'min' => 50,
                                        'max' => 200,
                                        'step' => 1,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 120,
                        ],
                        'condition' => [
                                'view!' => 'default',
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .single-date' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        ],
                ]
        );
        
        $this->add_responsive_control(
                'box_gap',
                [
                        'label' => __( 'Box Gap', 'ultraaddons' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                                'px' => [
                                        'min' => 0,
                                        'max' => 50,
                                        'step' => 1,
                                ],
                        ],
                        'default' => [
                                'unit' => 'px',
                                'size' => 10,
                        ],
                        'selectors' => [
                                '{{WRAPPER}} .single-date' => 'margin: 0 {{SIZE}}{{UNIT}};',
                        ],
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
            'bg_color',
            [
                'label'     => __( 'Background', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'time_color',
            [
                'label'     => __( 'Time Text Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date span.timer_int' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'label_color',
            [
                'label'     => __( 'Label Color', 'ultraaddons' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single-date span.timer_label' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name' => 'border',
                        'label' => __( 'Border', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .single-date',
                ]
        );
        
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name' => 'box_shadow',
                        'label' => __( 'Box Shadow', 'ultraaddons' ),
                        'selector' => '{{WRAPPER}} .single-date',
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
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'label_typography',
                        'label' => 'Label Typography',
                        'selector' => '{{WRAPPER}} .ua-coun-down-timer .timer_label',
                ]
        );
        
        
        $this->end_controls_section();
    }
       
    
}