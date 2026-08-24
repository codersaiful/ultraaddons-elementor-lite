<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * UltraAddons Event Calendar Widget
 *
 * Full interactive event calendar with Month, Week, Day, and List views,
 * Manual events repeater with General/Content tabs, Google Calendar & The Events Calendar integration,
 * 24-hour time format, custom start date, search, category filter, and modal popups.
 *
 * @since 2.0.4
 * @package UltraAddons
 */
class Event_Calendar extends Base {

	/**
	 * Constructor — register style and script dependencies
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'ultraaddons-event-calendar',
			ULTRA_ADDONS_ASSETS . 'css/widgets/event-calendar.css',
			[],
			ULTRA_ADDONS_VERSION,
			'all'
		);
		wp_enqueue_style( 'ultraaddons-event-calendar' );

		wp_register_script(
			'ultraaddons-event-calendar',
			ULTRA_ADDONS_ASSETS . 'js/frontend-event-calendar.js',
			[ 'jquery' ],
			ULTRA_ADDONS_VERSION,
			true
		);
		wp_enqueue_script( 'ultraaddons-event-calendar' );
	}

	public function get_style_depends() {
		return [ 'ultraaddons-event-calendar' ];
	}

	public function get_script_depends() {
		return [ 'jquery', 'ultraaddons-event-calendar' ];
	}

	public function is_reload_preview_required() {
		return true;
	}

	public function get_keywords() {
		return [ 'ultraaddons-elementor-lite', 'ua', 'event', 'calendar', 'schedule', 'agenda', 'date', 'timeline', 'planner', 'google calendar' ];
	}

	/**
	 * Register Widget Controls
	 */
	protected function register_controls() {
		$this->content_events_controls();
		$this->content_google_calendar_controls();
		$this->content_the_events_calendar_controls();
		$this->content_calendar_controls();
		$this->content_table_controls();

		$this->style_calendar_box_controls();
		$this->style_header_title_controls();
		$this->style_header_buttons_controls();
		$this->style_weekdays_controls();
		$this->style_day_cells_controls();
		$this->style_event_pills_controls();
		$this->style_modal_popup_controls();

		$this->style_table_controls();
	}

	/*----------------------------------------------------------------------
	 * CONTENT TAB: 1. EVENTS SECTION (MANUAL & SOURCES)
	 *--------------------------------------------------------------------*/
	protected function content_events_controls() {
		$this->start_controls_section(
			'section_events',
			[
				'label' => esc_html__( 'Events', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'event_source',
			[
				'label'   => esc_html__( 'Source', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual'              => esc_html__( 'Manual', 'ultraaddons-elementor-lite' ),
					'google'              => esc_html__( 'Google Calendar', 'ultraaddons-elementor-lite' ),
					'the_events_calendar' => esc_html__( 'The Events Calendar', 'ultraaddons-elementor-lite' ),
					'post'                => esc_html__( 'WordPress Posts / CPT', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'event_layout',
			[
				'label'   => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'calendar',
				'options' => [
					'calendar' => esc_html__( 'Calendar', 'ultraaddons-elementor-lite' ),
					'table'    => esc_html__( 'Table', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'event_repeater_tabs' );

		// Repeater Tab 1: General
		$repeater->start_controls_tab(
			'tab_event_general',
			[
				'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'Event Title', 'ultraaddons-elementor-lite' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'         => esc_html__( 'Event Link', 'ultraaddons-elementor-lite' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => [ 'active' => true ],
				'placeholder'   => esc_html__( 'https://example.com', 'ultraaddons-elementor-lite' ),
				'show_external' => true,
			]
		);

		$repeater->add_control(
			'redirect_to_link',
			[
				'label'       => esc_html__( 'Redirect to Event Link', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'The popup will not appear and you will be redirected to the Event Link directly.', 'ultraaddons-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'all_day',
			[
				'label'   => esc_html__( 'All Day', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$repeater->add_control(
			'start_date',
			[
				'label'       => esc_html__( 'Start Date & Time', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => current_time( 'Y-m-d H:i' ),
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'end_date',
			[
				'label'       => esc_html__( 'End Date & Time', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => current_time( 'Y-m-d H:i' ),
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'color',
			[
				'label'   => esc_html__( 'Event Background Color', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#4a2db6',
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label'   => esc_html__( 'Event Text Color', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);

		$repeater->add_control(
			'ribbon_color',
			[
				'label'   => esc_html__( 'Popup Ribbon Color', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#4a2db6',
			]
		);

		$repeater->add_control(
			'location',
			[
				'label'       => esc_html__( 'Location', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => 'San Francisco, CA',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'category',
			[
				'label'       => esc_html__( 'Category', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => 'Conference',
				'label_block' => true,
			]
		);

		$repeater->end_controls_tab();

		// Repeater Tab 2: Content
		$repeater->start_controls_tab(
			'tab_event_content',
			[
				'label' => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Join industry leaders and developers for an immersive event featuring cutting-edge tech sessions and workshops.', 'ultraaddons-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Featured Image (Popup Banner)', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'btn_text',
			[
				'label'   => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
				'default' => esc_html__( 'Register Now', 'ultraaddons-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'btn_url',
			[
				'label'       => esc_html__( 'Button Link', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [ 'active' => true ],
				'placeholder' => 'https://your-event-link.com',
				'default'     => [
					'url' => '#',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$curr_year  = current_time( 'Y' );
		$curr_month = current_time( 'm' );

		$this->add_control(
			'events_list',
			[
				'label'       => esc_html__( 'Events List', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => [
					'event_source' => 'manual',
				],
				'default'     => [
					[
						'title'       => '12:00 AM',
						'category'    => 'Meeting',
						'color'       => '#4a2db6',
						'text_color'  => '#ffffff',
						'start_date'  => current_time( 'Y-m-d 00:00' ),
						'end_date'    => current_time( 'Y-m-d 01:00' ),
						'location'    => 'HQ Room A',
						'description' => 'Daily midnight sync and feature sprint review.',
						'btn_text'    => 'Join Meeting',
					],
					[
						'title'       => 'Global AI & Cloud Summit',
						'category'    => 'Conference',
						'color'       => '#4a2db6',
						'text_color'  => '#ffffff',
						'start_date'  => $curr_year . '-' . $curr_month . '-05 09:30',
						'end_date'    => $curr_year . '-' . $curr_month . '-05 17:00',
						'location'    => 'Convention Center, San Francisco',
						'description' => 'Explore the breakthrough trends in cloud architecture and enterprise generative AI solutions.',
						'btn_text'    => 'Register Now',
					],
					[
						'title'       => 'UI/UX Design Masterclass',
						'category'    => 'Workshop',
						'color'       => '#f39c12',
						'text_color'  => '#ffffff',
						'start_date'  => $curr_year . '-' . $curr_month . '-14 14:00',
						'end_date'    => $curr_year . '-' . $curr_month . '-14 18:00',
						'location'    => 'Online Zoom Webinar',
						'description' => 'Hands-on live session on crafting modern design systems and interactive micro-interactions.',
						'btn_text'    => 'Join Masterclass',
					],
					[
						'title'       => 'Cybersecurity Hackathon 2026',
						'category'    => 'Hackathon',
						'color'       => '#f43f5e',
						'text_color'  => '#ffffff',
						'start_date'  => $curr_year . '-' . $curr_month . '-28 10:00',
						'end_date'    => $curr_year . '-' . $curr_month . '-28 20:00',
						'all_day'     => 'yes',
						'location'    => 'Tech Park Hall B',
						'description' => 'Compete for prizes in defending live infrastructure against modern penetration vectors.',
						'btn_text'    => 'Apply as Team',
					],
				],
			]
		);

		// Post query controls
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options    = [];
		foreach ( $post_types as $pt ) {
			$options[ $pt->name ] = $pt->label;
		}

		$this->add_control(
			'query_post_type',
			[
				'label'     => esc_html__( 'Post Type', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'post',
				'options'   => $options,
				'condition' => [
					'event_source' => 'post',
				],
			]
		);

		$this->add_control(
			'query_posts_per_page',
			[
				'label'     => esc_html__( 'Max Posts Count', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'min'       => 1,
				'max'       => 100,
				'condition' => [
					'event_source' => 'post',
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label'     => esc_html__( 'Order', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => [
					'ASC'  => esc_html__( 'Ascending (ASC)', 'ultraaddons-elementor-lite' ),
					'DESC' => esc_html__( 'Descending (DESC)', 'ultraaddons-elementor-lite' ),
				],
				'condition' => [
					'event_source' => 'post',
				],
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * CONTENT TAB: GOOGLE CALENDAR
	 *--------------------------------------------------------------------*/
	protected function content_google_calendar_controls() {
		$this->start_controls_section(
			'section_google_calendar',
			[
				'label'     => esc_html__( 'Google Calendar', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'event_source' => 'google',
				],
			]
		);

		$this->add_control(
			'google_api_key',
			[
				'label'       => esc_html__( 'API Key', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'google_calendar_id',
			[
				'label'       => esc_html__( 'Calendar ID', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'your_calendar_id@group.calendar.google.com',
			]
		);

		$this->add_control(
			'google_max_results',
			[
				'label'   => esc_html__( 'Max Results', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 50,
				'min'     => 1,
				'max'     => 250,
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * CONTENT TAB: THE EVENTS CALENDAR
	 *--------------------------------------------------------------------*/
	protected function content_the_events_calendar_controls() {
		$this->start_controls_section(
			'section_the_events_calendar',
			[
				'label'     => esc_html__( 'The Events Calendar', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'event_source' => 'the_events_calendar',
				],
			]
		);

		$is_tec_active = function_exists( 'tribe_get_events' ) || post_type_exists( 'tribe_events' );

		if ( ! $is_tec_active ) {
			$this->add_control(
				'tec_warning',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => esc_html__( 'The Events Calendar plugin is not installed or activated. Please activate it to pull events automatically.', 'ultraaddons-elementor-lite' ),
				]
			);
		} else {
			$this->add_control(
				'tec_max_results',
				[
					'label'   => esc_html__( 'Max Results', 'ultraaddons-elementor-lite' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => 1,
					'max'     => 250,
				]
			);
		}

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * CONTENT TAB: 2. CALENDAR SETTINGS SECTION
	 *--------------------------------------------------------------------*/
	protected function content_calendar_controls() {
		$this->start_controls_section(
			'section_calendar_settings',
			[
				'label'     => esc_html__( 'Calendar', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'event_layout' => 'calendar',
				],
			]
		);

		$this->add_control(
			'calendar_language',
			[
				'label'   => esc_html__( 'Language', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'en',
				'options' => [
					'en'    => esc_html__( 'English', 'ultraaddons-elementor-lite' ),
					'bn'    => esc_html__( 'Bengali', 'ultraaddons-elementor-lite' ),
					'es'    => esc_html__( 'Spanish', 'ultraaddons-elementor-lite' ),
					'fr'    => esc_html__( 'French', 'ultraaddons-elementor-lite' ),
					'de'    => esc_html__( 'German', 'ultraaddons-elementor-lite' ),
					'ar'    => esc_html__( 'Arabic', 'ultraaddons-elementor-lite' ),
					'hi'    => esc_html__( 'Hindi', 'ultraaddons-elementor-lite' ),
					'zh-cn' => esc_html__( 'Chinese', 'ultraaddons-elementor-lite' ),
					'it'    => esc_html__( 'Italian', 'ultraaddons-elementor-lite' ),
					'ja'    => esc_html__( 'Japanese', 'ultraaddons-elementor-lite' ),
					'pt'    => esc_html__( 'Portuguese', 'ultraaddons-elementor-lite' ),
					'ru'    => esc_html__( 'Russian', 'ultraaddons-elementor-lite' ),
					'tr'    => esc_html__( 'Turkish', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'time_format_24',
			[
				'label'        => esc_html__( '24-Hour Time Format', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'default_view',
			[
				'label'   => esc_html__( 'Default View', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'month',
				'options' => [
					'month' => esc_html__( 'Month', 'ultraaddons-elementor-lite' ),
					'week'  => esc_html__( 'Week', 'ultraaddons-elementor-lite' ),
					'day'   => esc_html__( 'Day', 'ultraaddons-elementor-lite' ),
					'list'  => esc_html__( 'List', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'start_date_type',
			[
				'label'   => esc_html__( 'Start Date', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'current' => esc_html__( 'Current Date', 'ultraaddons-elementor-lite' ),
					'custom'  => esc_html__( 'Custom Date', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'custom_start_date',
			[
				'label'          => '',
				'type'           => Controls_Manager::DATE_TIME,
				'default'        => current_time( 'Y-m-d' ),
				'label_block'    => true,
				'picker_options' => [
					'enableTime' => false,
					'dateFormat' => 'Y-m-d',
				],
				'condition'      => [
					'start_date_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'first_day',
			[
				'label'   => esc_html__( 'First Day of Week', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'Sunday', 'ultraaddons-elementor-lite' ),
					'1' => esc_html__( 'Monday', 'ultraaddons-elementor-lite' ),
					'2' => esc_html__( 'Tuesday', 'ultraaddons-elementor-lite' ),
					'3' => esc_html__( 'Wednesday', 'ultraaddons-elementor-lite' ),
					'4' => esc_html__( 'Thursday', 'ultraaddons-elementor-lite' ),
					'5' => esc_html__( 'Friday', 'ultraaddons-elementor-lite' ),
					'6' => esc_html__( 'Saturday', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'hide_popup_link',
			[
				'label'        => esc_html__( 'Hide Event Details Link', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Hide Event Details link in event popup', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'hide_old_events',
			[
				'label'   => esc_html__( 'Hide Old Events', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no'      => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
					'current' => esc_html__( 'Till Current Date', 'ultraaddons-elementor-lite' ),
					'start'   => esc_html__( 'Till Start Date', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'popup_btn_text',
			[
				'label'       => esc_html__( 'Event Details Text', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'Event Details', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'hide_popup_link!' => 'yes',
				],
			]
		);

		$this->add_control(
			'event_limit',
			[
				'label'       => esc_html__( 'Event Limit', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => 2,
				'description' => esc_html__( 'Limit the number of events displayed on a day. The rest will show up in a popover.', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'show_search',
			[
				'label'        => esc_html__( 'Show Search', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label'       => esc_html__( 'Search Placeholder', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
				'default'     => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'show_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_heading_formats',
			[
				'label'     => esc_html__( 'Calendar Table Heading Date Format', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_format_month',
			[
				'label'   => esc_html__( 'Month View', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''     => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
					'dddd' => esc_html__( 'Full Day Name', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'heading_format_week',
			[
				'label'   => esc_html__( 'Week View', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''         => esc_html__( 'Default', 'ultraaddons-elementor-lite' ),
					'ddd_do'   => wp_date( 'M j' ) . 'th',
					'dddd_do'  => wp_date( 'l j' ) . 'th',
					'dddd_d_y' => wp_date( 'l j/Y' ),
					'ddd_d_y'  => wp_date( 'M j/Y' ),
				],
			]
		);

		$this->add_control(
			'heading_popup_settings',
			[
				'label'     => esc_html__( 'Popup', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'popup_date_format',
			[
				'label'   => esc_html__( 'Date Format', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'MMM Do',
				'options' => [
					'MMM Do'     => wp_date( 'M jS' ),
					'MMMM Do'    => wp_date( 'F jS' ),
					'Do MMM'     => wp_date( 'jS M' ),
					'Do MMMM'    => wp_date( 'jS F' ),
					'MM-DD-YYYY' => wp_date( 'm-d-Y' ),
					'YYYY-DD-MM' => wp_date( 'Y-d-m' ),
					'YYYY-MM-DD' => wp_date( 'Y-d-m' ),
					'DD/MM/YYYY' => wp_date( 'd/m/Y' ),
					'MM/DD/YYYY' => wp_date( 'm/d/Y' ),
					'YYYY/MM/DD' => wp_date( 'Y/m/d' ),
					'DD.MM.YYYY' => wp_date( 'd.m.Y' ),
					'MM.DD.YYYY' => wp_date( 'm.d.Y' ),
					'YYYY.MM.DD' => wp_date( 'Y.m.d' ),
					'D-MMM-YYYY' => wp_date( 'j-M-Y' ),
					'MMMM YYYY'  => wp_date( 'F Y' ),
					'MMM YYYY'   => wp_date( 'M Y' ),
				],
			]
		);

		$this->add_control(
			'popup_ribbon_color',
			[
				'label'   => esc_html__( 'Ribbon Color', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#10ecab',
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * CONTENT TAB: 3. TABLE LAYOUT SECTION
	 *--------------------------------------------------------------------*/
	protected function content_table_controls() {
		$this->start_controls_section(
			'section_table_settings',
			[
				'label'     => esc_html__( 'Calendar', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'event_layout' => 'table',
				],
			]
		);

		$this->add_control(
			'table_start_date_type',
			[
				'label'   => esc_html__( 'Start Date', 'ultraaddons-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => [
					'current' => esc_html__( 'Current Day', 'ultraaddons-elementor-lite' ),
					'custom'  => esc_html__( 'Custom Date', 'ultraaddons-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'table_custom_start_date',
			[
				'label'          => '',
				'type'           => Controls_Manager::DATE_TIME,
				'default'        => current_time( 'Y-m-d' ),
				'label_block'    => true,
				'picker_options' => [
					'enableTime' => false,
					'dateFormat' => 'Y-m-d',
				],
				'condition'      => [
					'table_start_date_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'table_show_search',
			[
				'label'        => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'table_search_placeholder',
			[
				'label'       => esc_html__( 'Placeholder', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
				'default'     => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'table_show_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_search_label',
			[
				'label'       => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Search', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'table_show_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_show_title',
			[
				'label'        => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'table_title_label',
			[
				'label'       => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'default'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'table_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_show_description',
			[
				'label'        => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'table_desc_label',
			[
				'label'       => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'default'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'table_show_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_desc_limit',
			[
				'label'       => esc_html__( 'Word Count', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => 20,
				'default'     => 20,
				'min'         => 0,
				'condition'   => [
					'table_show_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_show_date',
			[
				'label'        => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'table_date_label',
			[
				'label'       => esc_html__( 'Label', 'ultraaddons-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
				'default'     => esc_html__( 'Date', 'ultraaddons-elementor-lite' ),
				'condition'   => [
					'table_show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_show_pagination',
			[
				'label'        => esc_html__( 'Pagination', 'ultraaddons-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'table_item_per_page',
			[
				'label'     => esc_html__( 'Item Per Page', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'default'   => 10,
				'condition' => [
					'table_show_pagination' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: CALENDAR CONTAINER
	 *--------------------------------------------------------------------*/
	protected function style_calendar_box_controls() {
		$this->start_controls_section(
			'section_style_calendar_box',
			[
				'label'     => esc_html__( 'Calendar', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'event_layout' => 'calendar',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'calendar_bg',
				'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-event-calendar',
			]
		);

		$this->add_control(
			'calendar_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-calendar, {{WRAPPER}} .ua-calendar-days-grid, {{WRAPPER}} .ua-cal-day-cell, {{WRAPPER}} .ua-calendar-weekdays, {{WRAPPER}} .ua-cal-weekday, {{WRAPPER}} .ua-calendar-week-view, {{WRAPPER}} .ua-cal-week-col, {{WRAPPER}} .ua-cal-week-col-head' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'calendar_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-calendar',
			]
		);

		$this->add_responsive_control(
			'calendar_inside_padding',
			[
				'label'      => esc_html__( 'Inside Space (Padding)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-calendar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'calendar_outside_margin',
			[
				'label'      => esc_html__( 'Outside Space (Margin)', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-calendar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: HEADER TITLE
	 *--------------------------------------------------------------------*/
	protected function style_header_title_controls() {
		$this->start_controls_section(
			'section_style_header_title',
			[
				'label' => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_title_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-calendar-title, {{WRAPPER}} .ua-calendar-title .ua-title-month, {{WRAPPER}} .ua-calendar-title .ua-title-year' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_title_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-calendar-title, {{WRAPPER}} .ua-calendar-title .ua-title-month',
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: HEADER BUTTONS (NAV & VIEWS)
	 *--------------------------------------------------------------------*/
	protected function style_header_buttons_controls() {
		$this->start_controls_section(
			'section_style_header_buttons',
			[
				'label' => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'buttons_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-cal-btn, {{WRAPPER}} .ua-view-btn',
			]
		);

		$this->start_controls_tabs( 'calendar_buttons_tabs' );

		// Normal
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'button_color_normal',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-btn, {{WRAPPER}} .ua-view-btn:not(.ua-active)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_normal',
			[
				'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-btn, {{WRAPPER}} .ua-view-btn:not(.ua-active)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-btn:hover, {{WRAPPER}} .ua-view-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_hover',
			[
				'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-btn:hover, {{WRAPPER}} .ua-view-btn:not(.ua-active):hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Active
		$this->start_controls_tab(
			'tab_button_active',
			[
				'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ),
			]
		);

		$this->add_control(
			'button_color_active',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-view-btn.ua-active' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_bg_active',
			[
				'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-view-btn.ua-active' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: DAY NAMES (WEEKDAYS)
	 *--------------------------------------------------------------------*/
	protected function style_weekdays_controls() {
		$this->start_controls_section(
			'section_style_weekdays',
			[
				'label' => esc_html__( 'Day Header', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'weekday_bg',
			[
				'label'     => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-calendar-weekdays' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weekday_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-weekday' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'weekday_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-cal-weekday',
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: DAY CELLS & TODAY
	 *--------------------------------------------------------------------*/
	protected function style_day_cells_controls() {
		$this->start_controls_section(
			'section_style_day_cells',
			[
				'label' => esc_html__( 'Days', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'day_cell_bg',
			[
				'label'     => esc_html__( 'Cell Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-day-cell' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'day_cell_today_bg',
			[
				'label'     => esc_html__( 'Current Date Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-day-today' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'day_cell_today_color',
			[
				'label'     => esc_html__( 'Current Date Number Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-cal-day-today .ua-cal-day-num' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'day_number_typography',
				'label'    => esc_html__( 'Date Number Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-cal-day-num',
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: EVENT PILLS / BADGES
	 *--------------------------------------------------------------------*/
	protected function style_event_pills_controls() {
		$this->start_controls_section(
			'section_style_event_pills',
			[
				'label'     => esc_html__( 'Event Badges', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'event_layout' => 'calendar',
				],
			]
		);

		$this->add_control(
			'event_pill_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-pill, {{WRAPPER}} .ua-event-pill-title, {{WRAPPER}} .ua-timegrid-event, {{WRAPPER}} .ua-timegrid-event-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'event_pill_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-pill, {{WRAPPER}} .ua-timegrid-event' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'event_pill_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-pill, {{WRAPPER}} .ua-event-pill-title, {{WRAPPER}} .ua-timegrid-event, {{WRAPPER}} .ua-timegrid-event-title',
			]
		);

		$this->add_responsive_control(
			'event_pill_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-pill, {{WRAPPER}} .ua-timegrid-event' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'event_pill_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-pill, {{WRAPPER}} .ua-timegrid-event' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: MODAL POPUP
	 *--------------------------------------------------------------------*/
	protected function style_modal_popup_controls() {
		$this->start_controls_section(
			'section_style_modal_popup',
			[
				'label' => esc_html__( 'Event Popup', 'ultraaddons-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'modal_dialog_bg',
				'label'    => esc_html__( 'Dialog Background', 'ultraaddons-elementor-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-event-modal-dialog',
			]
		);

		$this->add_responsive_control(
			'modal_dialog_border_radius',
			[
				'label'      => esc_html__( 'Dialog Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-modal-dialog' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'modal_dialog_shadow',
				'label'    => esc_html__( 'Box Shadow', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-modal-dialog',
			]
		);

		$this->add_control(
			'heading_modal_category',
			[
				'label'     => esc_html__( 'Category Ribbon', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'modal_category_bg',
			[
				'label'     => esc_html__( 'Ribbon Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-category' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'modal_category_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-category' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'modal_category_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-modal-category',
			]
		);

		$this->add_control(
			'heading_modal_title',
			[
				'label'     => esc_html__( 'Title', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'modal_title_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'modal_title_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-modal-title',
			]
		);

		$this->add_control(
			'heading_modal_meta',
			[
				'label'     => esc_html__( 'Date, Time & Location Meta', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'modal_meta_box_bg',
			[
				'label'     => esc_html__( 'Meta Box Background', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-meta-list' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_meta_text_color',
			[
				'label'     => esc_html__( 'Meta Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-meta-item, {{WRAPPER}} .ua-event-modal-meta-item span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'modal_meta_typography',
				'label'    => esc_html__( 'Meta Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-modal-meta-item, {{WRAPPER}} .ua-event-modal-meta-item span',
			]
		);

		$this->add_control(
			'heading_modal_desc',
			[
				'label'     => esc_html__( 'Description', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'modal_desc_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'modal_desc_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-modal-desc',
			]
		);

		$this->add_control(
			'heading_modal_button',
			[
				'label'     => esc_html__( 'Button', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'modal_btn_color',
			[
				'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-btn' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'modal_btn_bg',
			[
				'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-modal-btn' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'modal_btn_typography',
				'label'    => esc_html__( 'Typography', 'ultraaddons-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ua-event-modal-btn',
			]
		);

		$this->add_responsive_control(
			'modal_btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-modal-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/*----------------------------------------------------------------------
	 * STYLE TAB: TABLE LAYOUT CONTROLS
	 *--------------------------------------------------------------------*/
	protected function style_table_controls() {
		$this->start_controls_section(
			'section_style_table_layout',
			[
				'label'     => esc_html__( 'Table', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'event_layout' => 'table',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'table_bg',
				'label'    => esc_html__( 'Background', 'ultraaddons-elementor-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-event-table',
			]
		);

		$this->add_responsive_control(
			'table_margin',
			[
				'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-table-responsive' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_table_header',
			[
				'label'     => esc_html__( 'Header', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'event_layout' => 'table',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'table_header_typography',
				'selector' => '{{WRAPPER}} .ua-event-table thead tr th',
			]
		);

		$this->add_control(
			'table_header_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#181818',
				'selectors' => [
					'{{WRAPPER}} .ua-event-table thead tr th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'table_header_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ua-event-table thead tr th',
			]
		);

		$this->add_responsive_control(
			'table_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-table thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_table_body',
			[
				'label'     => esc_html__( 'Body', 'ultraaddons-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'event_layout' => 'table',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'table_body_typography',
				'selector' => '{{WRAPPER}} .ua-event-table tbody tr td',
			]
		);

		$this->add_control(
			'table_body_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-event-table tbody tr td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_body_padding',
			[
				'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ua-event-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Events Data from WP Posts
	 */
	protected function get_wp_posts_events( $settings ) {
		$post_type      = ! empty( $settings['query_post_type'] ) ? $settings['query_post_type'] : 'post';
		$posts_per_page = ! empty( $settings['query_posts_per_page'] ) ? (int) $settings['query_posts_per_page'] : 20;
		$order          = ! empty( $settings['query_order'] ) ? $settings['query_order'] : 'DESC';

		$query = new \WP_Query([
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'order'          => $order,
			'post_status'    => 'publish',
		]);

		$events = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id   = get_the_ID();
				$thumbnail = get_the_post_thumbnail_url( $post_id, 'medium_large' );

				$events[] = [
					'title'            => get_the_title(),
					'category'         => get_post_type_object( $post_type ) ? get_post_type_object( $post_type )->labels->singular_name : 'Post',
					'color'            => '#4a2db6',
					'text_color'       => '#ffffff',
					'ribbon_color'     => '#4a2db6',
					'start_date'       => get_the_date( 'Y-m-d H:i' ),
					'end_date'         => '',
					'all_day'          => 'no',
					'location'         => '',
					'description'      => wp_strip_all_tags( get_the_excerpt() ),
					'image'            => $thumbnail ? $thumbnail : '',
					'link'             => get_permalink(),
					'redirect_to_link' => 'no',
					'btn_text'         => ! empty( $settings['popup_btn_text'] ) ? $settings['popup_btn_text'] : esc_html__( 'Event Details', 'ultraaddons-elementor-lite' ),
					'btn_url'          => get_permalink(),
				];
			}
			wp_reset_postdata();
		}

		return $events;
	}

	/**
	 * Render Widget output in frontend
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$source = ! empty( $settings['event_source'] ) ? $settings['event_source'] : 'manual';

		$events = [];
		if ( 'post' === $source ) {
			$events = $this->get_wp_posts_events( $settings );
		} elseif ( 'the_events_calendar' === $source && post_type_exists( 'tribe_events' ) ) {
			$tec_events = tribe_get_events( [ 'posts_per_page' => ! empty( $settings['tec_max_results'] ) ? (int) $settings['tec_max_results'] : 50 ] );
			if ( ! empty( $tec_events ) ) {
				foreach ( $tec_events as $t_ev ) {
					$thumbnail = get_the_post_thumbnail_url( $t_ev->ID, 'medium_large' );
					$events[] = [
						'title'            => $t_ev->post_title,
						'category'         => 'Event',
						'color'            => '#4a2db6',
						'text_color'       => '#ffffff',
						'ribbon_color'     => '#4a2db6',
						'start_date'       => tribe_get_start_date( $t_ev->ID, false, 'Y-m-d H:i' ),
						'end_date'         => tribe_get_end_date( $t_ev->ID, false, 'Y-m-d H:i' ),
						'all_day'          => tribe_event_is_all_day( $t_ev->ID ) ? 'yes' : 'no',
						'location'         => tribe_get_venue( $t_ev->ID ),
						'description'      => wp_strip_all_tags( $t_ev->post_content ),
						'image'            => $thumbnail ? $thumbnail : '',
						'link'             => get_permalink( $t_ev->ID ),
						'redirect_to_link' => 'no',
						'btn_text'         => ! empty( $settings['popup_btn_text'] ) ? $settings['popup_btn_text'] : esc_html__( 'Event Details', 'ultraaddons-elementor-lite' ),
						'btn_url'          => get_permalink( $t_ev->ID ),
					];
				}
			}
		} else {
			$raw_events = ! empty( $settings['events_list'] ) ? $settings['events_list'] : [];
			foreach ( $raw_events as $ev ) {
				$img_url = '';
				if ( ! empty( $ev['image']['url'] ) ) {
					$img_url = $ev['image']['url'];
				}

				$events[] = [
					'title'            => ! empty( $ev['title'] ) ? $ev['title'] : '',
					'category'         => ! empty( $ev['category'] ) ? $ev['category'] : '',
					'color'            => ! empty( $ev['color'] ) ? $ev['color'] : '#4a2db6',
					'text_color'       => ! empty( $ev['text_color'] ) ? $ev['text_color'] : '#ffffff',
					'ribbon_color'     => ! empty( $ev['ribbon_color'] ) ? $ev['ribbon_color'] : '#4a2db6',
					'start_date'       => ! empty( $ev['start_date'] ) ? $ev['start_date'] : current_time( 'Y-m-d H:i' ),
					'end_date'         => ! empty( $ev['end_date'] ) ? $ev['end_date'] : '',
					'all_day'          => isset( $ev['all_day'] ) ? $ev['all_day'] : 'no',
					'location'         => ! empty( $ev['location'] ) ? $ev['location'] : '',
					'description'      => ! empty( $ev['description'] ) ? $ev['description'] : '',
					'image'            => $img_url,
					'link'             => ! empty( $ev['link']['url'] ) ? $ev['link']['url'] : '',
					'redirect_to_link' => ( 'yes' === ( isset( $ev['redirect_to_link'] ) ? $ev['redirect_to_link'] : '' ) ) ? 'yes' : 'no',
					'btn_text'         => ! empty( $ev['btn_text'] ) ? $ev['btn_text'] : ( ! empty( $settings['popup_btn_text'] ) ? $settings['popup_btn_text'] : 'Event Details' ),
					'btn_url'          => ! empty( $ev['btn_url']['url'] ) ? $ev['btn_url']['url'] : ( ! empty( $ev['link']['url'] ) ? $ev['link']['url'] : '#' ),
				];
			}
		}

		// Collect unique categories for filter bar
		$categories = [];
		foreach ( $events as $ev ) {
			if ( ! empty( $ev['category'] ) && ! in_array( $ev['category'], $categories, true ) ) {
				$categories[] = $ev['category'];
			}
		}		$layout = ! empty( $settings['event_layout'] ) ? $settings['event_layout'] : 'calendar';

		$calendar_settings = [
			'layout'                   => $layout,
			'language'                 => ! empty( $settings['calendar_language'] ) ? $settings['calendar_language'] : 'en',
			'default_view'         => ! empty( $settings['default_view'] ) ? $settings['default_view'] : 'month',
			'first_day'            => isset( $settings['first_day'] ) ? $settings['first_day'] : '0',
			'time_format_24'       => ( 'yes' === ( isset( $settings['time_format_24'] ) ? $settings['time_format_24'] : 'no' ) ),
			'start_date_type'      => ! empty( $settings['start_date_type'] ) ? $settings['start_date_type'] : 'custom',
			'custom_start_date'    => ! empty( $settings['custom_start_date'] ) ? $settings['custom_start_date'] : '',
			'event_limit'          => ! empty( $settings['event_limit'] ) ? (int) $settings['event_limit'] : 3,
			'hide_old_events'      => ! empty( $settings['hide_old_events'] ) ? $settings['hide_old_events'] : 'no',
			'hide_popup_link'      => ( 'yes' === ( isset( $settings['hide_popup_link'] ) ? $settings['hide_popup_link'] : 'no' ) ),
			'heading_format_month' => ! empty( $settings['heading_format_month'] ) ? $settings['heading_format_month'] : '',
			'heading_format_week'  => ! empty( $settings['heading_format_week'] ) ? $settings['heading_format_week'] : '',
			'popup_date_format'    => ! empty( $settings['popup_date_format'] ) ? $settings['popup_date_format'] : 'MMM Do',
			'popup_ribbon_color'   => ! empty( $settings['popup_ribbon_color'] ) ? $settings['popup_ribbon_color'] : '#10ecab',
			'event_source'         => $source,
			'google_api_key'       => ! empty( $settings['google_api_key'] ) ? $settings['google_api_key'] : '',
			'google_calendar_id'   => ! empty( $settings['google_calendar_id'] ) ? $settings['google_calendar_id'] : '',
			'google_max_results'   => ! empty( $settings['google_max_results'] ) ? (int) $settings['google_max_results'] : 50,
			// Table settings
			'table_start_date_type'    => ! empty( $settings['table_start_date_type'] ) ? $settings['table_start_date_type'] : 'current',
			'table_custom_start_date'  => ! empty( $settings['table_custom_start_date'] ) ? $settings['table_custom_start_date'] : '',
			'table_show_search'        => ( 'yes' === ( isset( $settings['table_show_search'] ) ? $settings['table_show_search'] : 'yes' ) ),
			'table_search_placeholder' => ! empty( $settings['table_search_placeholder'] ) ? $settings['table_search_placeholder'] : 'Search',
			'table_search_label'       => ! empty( $settings['table_search_label'] ) ? $settings['table_search_label'] : '',
			'table_show_title'         => ( 'yes' === ( isset( $settings['table_show_title'] ) ? $settings['table_show_title'] : 'yes' ) ),
			'table_title_label'        => ! empty( $settings['table_title_label'] ) ? $settings['table_title_label'] : 'Title',
			'table_show_description'   => ( 'yes' === ( isset( $settings['table_show_description'] ) ? $settings['table_show_description'] : 'yes' ) ),
			'table_desc_label'         => ! empty( $settings['table_desc_label'] ) ? $settings['table_desc_label'] : 'Description',
			'table_desc_limit'         => isset( $settings['table_desc_limit'] ) ? (int) $settings['table_desc_limit'] : 20,
			'table_show_date'          => ( 'yes' === ( isset( $settings['table_show_date'] ) ? $settings['table_show_date'] : 'yes' ) ),
			'table_date_label'         => ! empty( $settings['table_date_label'] ) ? $settings['table_date_label'] : 'Date',
			'table_show_pagination'    => ( 'yes' === ( isset( $settings['table_show_pagination'] ) ? $settings['table_show_pagination'] : 'yes' ) ),
			'table_item_per_page'      => ! empty( $settings['table_item_per_page'] ) ? (int) $settings['table_item_per_page'] : 10,
		];

		$show_search        = ( 'yes' === ( isset( $settings['show_search'] ) ? $settings['show_search'] : 'no' ) );
		$search_placeholder = ! empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : esc_html__( 'Search events...', 'ultraaddons-elementor-lite' );
		$show_categories    = ( 'yes' === ( isset( $settings['show_categories'] ) ? $settings['show_categories'] : 'no' ) );
		$show_views         = ( 'yes' === ( isset( $settings['show_views'] ) ? $settings['show_views'] : 'yes' ) );
		$show_nav           = ( 'yes' === ( isset( $settings['show_nav'] ) ? $settings['show_nav'] : 'yes' ) );
		?>
		<div class="ua-event-calendar <?php echo ( 'table' === $layout ) ? 'ua-layout-table' : 'ua-layout-calendar'; ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $calendar_settings ) ); ?>"
			data-events="<?php echo esc_attr( wp_json_encode( $events ) ); ?>">

			<?php if ( 'calendar' === $layout ) : ?>
				<?php if ( $show_search || ( $show_categories && ! empty( $categories ) ) ) : ?>
					<div class="ua-calendar-top-bar">
						<?php if ( $show_categories && ! empty( $categories ) ) : ?>
							<div class="ua-calendar-categories-bar">
								<button type="button" class="ua-cal-cat-btn ua-active" data-category="all">
									<?php echo esc_html__( 'All Events', 'ultraaddons-elementor-lite' ); ?>
								</button>
								<?php foreach ( $categories as $cat ) : ?>
									<button type="button" class="ua-cal-cat-btn" data-category="<?php echo esc_attr( $cat ); ?>">
										<?php echo esc_html( $cat ); ?>
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( $show_search ) : ?>
							<div class="ua-calendar-search-box">
								<input type="text" class="ua-calendar-search-input" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" />
								<span class="ua-calendar-search-icon">🔍</span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="ua-calendar-header">
					<?php if ( $show_nav ) : ?>
						<div class="ua-calendar-nav">
							<div class="ua-cal-btn-group">
								<button type="button" class="ua-cal-btn ua-cal-btn-prev" title="<?php echo esc_attr__( 'Previous', 'ultraaddons-elementor-lite' ); ?>">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
								</button>
								<button type="button" class="ua-cal-btn ua-cal-btn-next" title="<?php echo esc_attr__( 'Next', 'ultraaddons-elementor-lite' ); ?>">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
								</button>
							</div>
							<button type="button" class="ua-cal-btn ua-cal-btn-today"><?php echo esc_html__( 'Today', 'ultraaddons-elementor-lite' ); ?></button>
						</div>
					<?php endif; ?>

					<h3 class="ua-calendar-title"></h3>

					<?php if ( $show_views ) : ?>
						<div class="ua-calendar-views">
							<button type="button" class="ua-view-btn <?php echo ( 'day' === $calendar_settings['default_view'] ) ? 'ua-active' : ''; ?>" data-view="day">
								<span class="ua-view-btn-icon">
									<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
								</span>
								<span class="ua-view-btn-text"><?php echo esc_html__( 'Day', 'ultraaddons-elementor-lite' ); ?></span>
							</button>
							<button type="button" class="ua-view-btn <?php echo ( 'week' === $calendar_settings['default_view'] ) ? 'ua-active' : ''; ?>" data-view="week">
								<span class="ua-view-btn-icon">
									<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line></svg>
								</span>
								<span class="ua-view-btn-text"><?php echo esc_html__( 'Week', 'ultraaddons-elementor-lite' ); ?></span>
							</button>
							<button type="button" class="ua-view-btn <?php echo ( 'month' === $calendar_settings['default_view'] ) ? 'ua-active' : ''; ?>" data-view="month">
								<span class="ua-view-btn-icon">
									<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="4" height="4" rx="1"></rect><rect x="10" y="3" width="4" height="4" rx="1"></rect><rect x="17" y="3" width="4" height="4" rx="1"></rect><rect x="3" y="10" width="4" height="4" rx="1"></rect><rect x="10" y="10" width="4" height="4" rx="1"></rect><rect x="17" y="10" width="4" height="4" rx="1"></rect><rect x="3" y="17" width="4" height="4" rx="1"></rect><rect x="10" y="17" width="4" height="4" rx="1"></rect><rect x="17" y="17" width="4" height="4" rx="1"></rect></svg>
								</span>
								<span class="ua-view-btn-text"><?php echo esc_html__( 'Month', 'ultraaddons-elementor-lite' ); ?></span>
							</button>
							<button type="button" class="ua-view-btn <?php echo ( 'list' === $calendar_settings['default_view'] ) ? 'ua-active' : ''; ?>" data-view="list">
								<span class="ua-view-btn-icon">
									<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
								</span>
								<span class="ua-view-btn-text"><?php echo esc_html__( 'List', 'ultraaddons-elementor-lite' ); ?></span>
							</button>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ua-calendar-body">
				<!-- Injected dynamically by JS -->
			</div>

			<!-- Event Details Modal Popup -->
			<div class="ua-event-modal" aria-hidden="true">
				<div class="ua-event-modal-overlay"></div>
				<div class="ua-event-modal-dialog">
					<!-- Modal body injected dynamically by JS -->
				</div>
			</div>

		</div>
		<?php
	}
}
