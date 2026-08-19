<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filterable Gallery Widget
 * 
 * UltraAddons Filterable Gallery Widget.
 *
 * Highly customizable, responsive, and accessible filterable media gallery.
 * 
 * @package UltraAddons
 * @since 1.1.4.6
 * @author CodeAstrology Team
 */
class Filterable_Gallery extends Base {

    /**
     * Constructor to register widget assets safely.
     */
    public function __construct( $data = [], $ultraaddons_args = null ) {
        parent::__construct( $data, $ultraaddons_args );

        // ImagesLoaded script
        wp_register_script(
            'imagesloaded.pkgd.min',
            ULTRA_ADDONS_ASSETS . 'vendor/js/imagesloaded.pkgd.min.js',
            [ 'jquery' ],
            ULTRA_ADDONS_VERSION,
            true
        );

        // Isotope script
        wp_register_script(
            'isotope.pkgd.min',
            ULTRA_ADDONS_ASSETS . 'vendor/js/isotope.pkgd.min.js',
            [ 'jquery', 'imagesloaded.pkgd.min' ],
            ULTRA_ADDONS_VERSION,
            true
        );

        $js_ver  = is_file( ULTRA_ADDONS_DIR . 'assets/js/frontend-filterable-gallery.js' ) ? filemtime( ULTRA_ADDONS_DIR . 'assets/js/frontend-filterable-gallery.js' ) : ULTRA_ADDONS_VERSION;
        $css_ver = is_file( ULTRA_ADDONS_DIR . 'assets/css/widgets/filterable-gallery.css' ) ? filemtime( ULTRA_ADDONS_DIR . 'assets/css/widgets/filterable-gallery.css' ) : ULTRA_ADDONS_VERSION;

        // Widget frontend script
        wp_register_script(
            'ultraaddons-filterable-gallery',
            ULTRA_ADDONS_ASSETS . 'js/frontend-filterable-gallery.js',
            [ 'jquery', 'isotope.pkgd.min', 'imagesloaded.pkgd.min' ],
            $js_ver,
            true
        );

        // Widget frontend CSS
        wp_register_style(
            'ultraaddons-filterable-gallery',
            ULTRA_ADDONS_ASSETS . 'css/widgets/filterable-gallery.css',
            [],
            $css_ver
        );
    }

    /**
     * Script dependencies for this widget.
     * Elementor automatically enqueues these when widget is loaded.
     *
     * @return array
     */
    public function get_script_depends() {
        return [
            'imagesloaded.pkgd.min',
            'isotope.pkgd.min',
            'ultraaddons-filterable-gallery',
        ];
    }

    /**
     * Style dependencies for this widget.
     * Elementor automatically enqueues these when widget is loaded.
     *
     * @return array
     */
    public function get_style_depends() {
        return [
            'ultraaddons-filterable-gallery',
            'elementor-icons-fa-solid',
            'elementor-icons-fa-regular',
            'elementor-icons-fa-brands',
        ];
    }

    /**
     * Keywords for Elementor search.
     *
     * @return array
     */
    public function get_keywords() {
        return [
            'ultraaddons',
            'gallery',
            'filterable gallery',
            'filter gallery',
            'portfolio',
            'image gallery',
            'photo gallery',
            'media grid',
            'masonry gallery',
            'video gallery',
        ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->register_settings_controls();
        $this->register_filter_controls();
        $this->register_items_controls();
        $this->register_load_more_controls();

        // Style sections
        $this->register_general_style_controls();
        $this->register_filter_style_controls();
        $this->register_item_style_controls();
        $this->register_mouseover_style_controls();
        $this->register_card_style_controls();
        $this->register_video_style_controls();
        $this->register_icon_style_controls();
        $this->register_price_style_controls();
        $this->register_ratings_style_controls();
        $this->register_category_style_controls();
        $this->register_search_form_style_controls();
        $this->register_not_found_style_controls();
        $this->register_load_more_style_controls();
    }

    /**
     * Register Content Tab -> Settings Section.
     */
    protected function register_settings_controls() {
        $this->start_controls_section(
            'section_fg_settings',
            [
                'label' => esc_html__( 'Settings', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_fg_caption_style',
            [
                'label'   => esc_html__( 'Layout', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hoverer',
                'options' => [
                    'hoverer'  => esc_html__( 'Overlay', 'ultraaddons-elementor-lite' ),
                    'card'     => esc_html__( 'Card', 'ultraaddons-elementor-lite' ),
                    'layout_3' => esc_html__( 'Search and Filter', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'ua_fg_items_to_show',
            [
                'label'   => esc_html__( 'Items to show', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'min'     => 1,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'ua_fg_filter_duration',
            [
                'label'     => esc_html__( 'Animation Duration (ms)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 500,
                'min'       => 100,
                'max'       => 2000,
                'step'      => 50,
                'condition' => [
                    'ua_fg_caption_style' => [ 'hoverer', 'card', 'layout_3' ],
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'   => esc_html__( 'Columns', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [ 'title' => '1', 'text' => '1' ],
                    '2' => [ 'title' => '2', 'text' => '2' ],
                    '3' => [ 'title' => '3', 'text' => '3' ],
                    '4' => [ 'title' => '4', 'text' => '4' ],
                    '5' => [ 'title' => '5', 'text' => '5' ],
                    '6' => [ 'title' => '6', 'text' => '6' ],
                ],
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'toggle'         => false,
            ]
        );

        $this->add_control(
            'ua_fg_grid_style',
            [
                'label'   => esc_html__( 'Grid Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'grid',
                'options' => [
                    'grid' => [
                        'title' => esc_html__( 'Grid', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-gallery-grid',
                    ],
                    'masonry' => [
                        'title' => esc_html__( 'Masonry', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-gallery-masonry',
                    ],
                ],
                'toggle'    => false,
                'condition' => [
                    'ua_fg_caption_style' => [ 'hoverer', 'card', 'layout_3' ],
                ],
            ]
        );

        $this->add_control(
            'ua_fg_grid_item_height',
            [
                'label'     => esc_html__( 'Image Height', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'condition' => [
                    'ua_fg_grid_style' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-item .ua-fg-thumb-wrap' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'ua_fg_image_resolution',
                'default'     => 'medium',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'hover_style_heading',
            [
                'label'     => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'ua_fg_caption_style' => 'hoverer',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_grid_hover_style',
            [
                'label'   => esc_html__( 'Style', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide-up',
                'options' => [
                    'none'     => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                    'slide-up' => esc_html__( 'Slide In Up', 'ultraaddons-elementor-lite' ),
                    'fade-in'  => esc_html__( 'Fade In', 'ultraaddons-elementor-lite' ),
                    'zoom-in'  => esc_html__( 'Zoom In', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_fg_caption_style' => 'hoverer',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_grid_hover_transition',
            [
                'label'   => esc_html__( 'Transition', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'max' => 4000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-overlay' => 'transition-duration: {{SIZE}}ms;',
                ],
                'condition' => [
                    'ua_fg_caption_style'    => 'hoverer',
                    'ua_fg_grid_hover_style!' => 'none',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'ua_fg_show_popup',
            [
                'label'   => esc_html__( 'Link to', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'media' => [
                        'title' => esc_html__( 'Media', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-e-image',
                    ],
                    'buttons' => [
                        'title' => esc_html__( 'Buttons', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-button',
                    ],
                ],
                'default' => 'buttons',
                'toggle'  => false,
            ]
        );

        $this->add_control(
            'ua_title_clickable',
            [
                'label'        => esc_html__( 'Title Clickable', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'ua_section_fg_full_image_clickable',
            [
                'label'        => esc_html__( 'Image Clickable', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'ua_section_fg_mfp_caption',
            [
                'label'        => esc_html__( 'Caption in Popup', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'ua_section_fg_zoom_icon_new',
            [
                'label'   => esc_html__( 'Lightbox Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-search-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_fg_show_popup' => 'buttons',
                    'ua_section_fg_full_image_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_section_fg_link_icon_new',
            [
                'label'   => esc_html__( 'Link Icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-link',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_fg_show_popup' => 'buttons',
                    'ua_section_fg_full_image_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_section_fg_full_image_action',
            [
                'label'   => esc_html__( 'Full Image Action', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => [
                    'lightbox' => esc_html__( 'Lightbox', 'ultraaddons-elementor-lite' ),
                    'link'     => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                ],
                'condition' => [
                    'ua_section_fg_full_image_clickable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_item_randomize',
            [
                'label'        => esc_html__( 'Randomize Item', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Items will be displayed in a random order.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_search_among_all',
            [
                'label'        => esc_html__( 'Search Full Gallery ?', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'ua_fg_caption_style' => 'layout_3',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_not_found_text',
            [
                'label'       => esc_html__( 'Not Found Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'No Items Found', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Not Found Text', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_fg_caption_style' => 'layout_3',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Content Tab -> Filterable Controls Section.
     */
    protected function register_filter_controls() {
        $this->start_controls_section(
            'section_fg_control_settings',
            [
                'label' => esc_html__( 'Filterable Controls', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'filter_enable',
            [
                'label'     => esc_html__( 'Filter', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Enable', 'ultraaddons-elementor-lite' ),
                'label_off' => esc_html__( 'Disable', 'ultraaddons-elementor-lite' ),
                'default'   => 'yes',
            ]
        );

        $this->add_control(
            'ua_fg_mobile_scroll_to_top',
            [
                'label'        => esc_html__( 'Scroll to Items on Mobile', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Enable this to automatically scroll to the items of the gallery on mobile devices after selecting a filter.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_fg_mobile_scroll_offset',
            [
                'label'     => esc_html__( 'Scroll Offset (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 0,
                'min'       => -500,
                'max'       => 500,
                'step'      => 1,
                'condition' => [
                    'ua_fg_mobile_scroll_to_top' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_all_label_text',
            [
                'label'     => esc_html__( 'Gallery All Label', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'All', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'filter_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fg_all_label_icon',
            [
                'label'   => esc_html__( 'All label icon', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-angle-down',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_fg_caption_style' => 'layout_3',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'       => esc_html__( 'Title Tag', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default'     => 'h2',
                'options'     => [
                    'h1'   => [ 'title' => 'H1', 'text' => 'H1' ],
                    'h2'   => [ 'title' => 'H2', 'text' => 'H2' ],
                    'h3'   => [ 'title' => 'H3', 'text' => 'H3' ],
                    'h4'   => [ 'title' => 'H4', 'text' => 'H4' ],
                    'h5'   => [ 'title' => 'H5', 'text' => 'H5' ],
                    'h6'   => [ 'title' => 'H6', 'text' => 'H6' ],
                    'span' => [ 'title' => 'SPAN', 'text' => 'SPAN' ],
                    'p'    => [ 'title' => 'P', 'text' => 'P' ],
                    'div'  => [ 'title' => 'DIV', 'text' => 'DIV' ],
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'ua_fg_control',
            [
                'label'       => esc_html__( 'Filter Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Gallery Filter', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'ua_fg_control_custom_id',
            [
                'label'       => esc_html__( 'Custom ID', 'ultraaddons-elementor-lite' ),
                'description' => esc_html__( 'Adding a custom ID will function as an anchor tag. For instance, if you input "test" as your custom ID, the link will change to "#test" and it will immediately open the corresponding tab.', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => '',
            ]
        );

        $repeater->add_control(
            'ua_fg_control_active_as_default',
            [
                'label'        => esc_html__( 'Active as Default', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'ua_fg_controls',
            [
                'label'       => esc_html__( 'Filter Categories', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ ua_fg_control }}}',
                'default'     => [
                    [ 'ua_fg_control' => esc_html__( 'Gallery Filter', 'ultraaddons-elementor-lite' ) ],
                ],
                'condition'   => [
                    'filter_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Content Tab -> Gallery Items Section.
     */
    protected function register_items_controls() {
        $this->start_controls_section(
            'section_fg_grid_settings',
            [
                'label' => esc_html__( 'Gallery Items', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'photo_gallery',
            [
                'label'     => esc_html__( 'Photo Gallery', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Enable', 'ultraaddons-elementor-lite' ),
                'label_off' => esc_html__( 'Disable', 'ultraaddons-elementor-lite' ),
                'default'   => 'yes',
            ]
        );

        $this->add_control(
            'video_gallery_yt_privacy',
            [
                'label'       => esc_html__( 'Video Privacy Mode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'If enabled, YouTube privacy-enhanced mode (youtube-nocookie.com) and Vimeo DNT will be used.', 'ultraaddons-elementor-lite' ),
                'default'     => '',
            ]
        );

        $this->add_control(
            'ua_privacy_notice_control',
            [
                'label'       => esc_html__( 'Consent Notice', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'   => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'default'     => '',
            ]
        );

        $this->add_control(
            'ua_privacy_notice',
            [
                'label'     => esc_html__( 'Privacy Notice', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [
                    'ua_privacy_notice_control' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_use_video_tag',
            [
                'label'        => esc_html__( 'Use Video Tag', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Enable this to use an HTML5 video tag instead of iframe for self-hosted videos.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'ua_fg_gallery_item_name',
            [
                'label'       => esc_html__( 'Gallery Item Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Gallery Item Name', 'ultraaddons-elementor-lite' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_control_name',
            [
                'label'       => esc_html__( 'Gallery Filter Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'description' => esc_html__( 'Select from dropdown or type category name. Separate multiple items with comma (e.g. Gallery Filter, Branding)', 'ultraaddons-elementor-lite' ),
                'placeholder' => esc_html__( 'Select or type category...', 'ultraaddons-elementor-lite' ),
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'fg_video_gallery_switch',
            [
                'label'        => esc_html__( 'Video Gallery Switch', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'false',
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_item_video_link',
            [
                'label'       => esc_html__( 'Link', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'https://www.youtube.com/watch?v=kB4U67tiQLA',
                'label_block' => true,
                'condition'   => [
                    'fg_video_gallery_switch' => 'true',
                ],
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_video_layout',
            [
                'label'   => esc_html__( 'Layout Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'horizontal' => [
                        'title' => esc_html__( 'Horizontal', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-justify-space-around-v',
                    ],
                    'vertical'   => [
                        'title' => esc_html__( 'Vertical', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-justify-space-around-h',
                    ],
                ],
                'default'   => 'horizontal',
                'condition' => [
                    'fg_video_gallery_switch' => 'true',
                ],
            ]
        );

        $repeater->add_control(
            'fg_item_price_switch',
            [
                'label'        => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'false',
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'fg_item_price',
            [
                'label'     => esc_html__( 'Value', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '$20.00',
                'condition' => [
                    'fg_item_price_switch' => 'true',
                ],
            ]
        );

        $repeater->add_control(
            'fg_item_ratings_switch',
            [
                'label'        => esc_html__( 'Ratings', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'false',
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'fg_item_ratings',
            [
                'label'     => esc_html__( 'Value', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '5',
                'condition' => [
                    'fg_item_ratings_switch' => 'true',
                ],
            ]
        );

        $repeater->add_control(
            'fg_item_cat_switch',
            [
                'label'        => esc_html__( 'Category', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'false',
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'fg_item_cat',
            [
                'label'     => esc_html__( 'Name', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'UltraAddons', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'fg_item_cat_switch' => 'true',
                ],
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_item_content',
            [
                'label'   => esc_html__( 'Content', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, provident.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_img',
            [
                'label'   => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_lightbox',
            [
                'label'        => esc_html__( 'Lightbox Button?', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'true',
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_link',
            [
                'label'        => esc_html__( 'Link Button?', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'true',
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'ua_fg_gallery_img_link',
            [
                'label'         => esc_html__( 'Custom Link URL', 'ultraaddons-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'label_block'   => true,
                'default'       => [
                    'url'         => '#',
                    'is_external' => '',
                ],
                'show_external' => true,
            ]
        );

        $this->add_control(
            'ua_fg_gallery_items',
            [
                'label'       => esc_html__( 'Items', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ ua_fg_gallery_item_name }}}',
                'default'     => [
                    [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                    [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                    [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                    [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                    [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                    [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Content Tab -> Load More Section.
     */
    protected function register_load_more_controls() {
        $this->start_controls_section(
            'section_pagination',
            [
                'label' => esc_html__( 'Load More Button', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label'   => esc_html__( 'Load More Button', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'images_per_page',
            [
                'label'     => esc_html__( 'Images Per Page', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 6,
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label'     => esc_html__( 'Button Label', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Load More', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'nomore_items_text',
            [
                'label'     => esc_html__( 'No More Items Text', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'No more items!', 'ultraaddons-elementor-lite' ),
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_btn_width',
            [
                'label'      => esc_html__( 'Button Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-load-more-btn' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_btn_height',
            [
                'label'      => esc_html__( 'Button Height', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-load-more-btn' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; padding-top: 0; padding-bottom: 0;',
                ],
                'condition'  => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [ 'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center'  => [ 'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right'   => [ 'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => esc_html__( 'Full Width (Justified)', 'ultraaddons-elementor-lite' ), 'icon' => 'eicon-text-align-justify' ],
                ],
                'default'   => 'center',
                'selectors_dictionary' => [
                    'left'    => 'text-align: left;',
                    'center'  => 'text-align: center;',
                    'right'   => 'text-align: right;',
                    'justify' => 'text-align: center;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-load-more-wrap' => '{{VALUE}}',
                    '{{WRAPPER}} .ua-fg-load-more-wrap button.ua-fg-load-more-btn' => '{{VALUE}}' === 'text-align: center;' && '{{load_more_align.VALUE}}' === 'justify' ? 'width: 100%; display: flex; justify-content: center;' : '',
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> General Container Styles.
     */
    protected function register_general_style_controls() {
        $this->start_controls_section(
            'section_fg_style_settings',
            [
                'label' => esc_html__( 'General', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_fg_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ua-filterable-gallery-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_container_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-filterable-gallery-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_container_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-filterable-gallery-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_fg_border',
                'selector' => '{{WRAPPER}} .ua-filterable-gallery-wrap',
            ]
        );

        $this->add_control(
            'ua_fg_border_radius',
            [
                'label'   => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [ 'size' => 0 ],
                'range'   => [ 'px' => [ 'max' => 500 ] ],
                'selectors' => [
                    '{{WRAPPER}} .ua-filterable-gallery-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_shadow',
                'selector' => '{{WRAPPER}} .ua-filterable-gallery-wrap',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Filter Bar Styles.
     */
    protected function register_filter_style_controls() {
        $this->start_controls_section(
            'section_fg_control_style_settings',
            [
                'label'     => esc_html__( 'Control', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_fg_caption_style!' => 'layout_3',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_control_bar',
            [
                'label'     => esc_html__( 'Bar', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'ua_fg_control_bar_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-controls' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_control_bar_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-controls-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_control_bar_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_control_bar_button',
            [
                'label'     => esc_html__( 'Buttons', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'ua_fg_control_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_control_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-control-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_control_typography',
                'selector' => '{{WRAPPER}} .ua-fg-filter-btn',
            ]
        );

        $this->start_controls_tabs( 'ua_fg_control_tabs' );

        // Normal
        $this->start_controls_tab(
            'ua_fg_control_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_fg_control_normal_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#444444',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-filter-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_control_normal_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-filter-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_fg_control_normal_border',
                'selector' => '{{WRAPPER}} .ua-fg-filter-btn',
            ]
        );

        $this->add_control(
            'ua_fg_control_normal_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_control_normal_box_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-filter-btn',
            ]
        );

        $this->end_controls_tab();

        // Active
        $this->start_controls_tab(
            'ua_fg_control_active',
            [ 'label' => esc_html__( 'Active', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_fg_control_active_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-filter-btn.ua-fg-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_control_active_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-filter-btn.ua-fg-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_fg_control_active_border',
                'selector' => '{{WRAPPER}} .ua-fg-filter-btn.ua-fg-active',
            ]
        );

        $this->add_control(
            'ua_fg_control_active_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-filter-btn.ua-fg-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_control_active_box_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-filter-btn.ua-fg-active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Item Style.
     */
    protected function register_item_style_controls() {
        $this->start_controls_section(
            'section_fg_item_style_settings',
            [
                'label' => esc_html__( 'Item', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_fg_item_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-item-card, {{WRAPPER}} .ua-fg-card-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_item_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-item-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_item_margin',
            [
                'label'      => esc_html__( 'Margin', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-item-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_fg_item_border',
                'selector' => '{{WRAPPER}} .ua-fg-item-card',
            ]
        );

        $this->add_control(
            'ua_fg_item_border_radius',
            [
                'label'   => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SLIDER,
                'range'   => [ 'px' => [ 'max' => 500 ] ],
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-item-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_item_box_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-item-card',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Mouseover Effect (Overlay).
     */
    protected function register_mouseover_style_controls() {
        $this->start_controls_section(
            'section_fg_hoverer_style_settings',
            [
                'label'     => esc_html__( 'Mouseover Effect', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_fg_caption_style' => 'hoverer',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_hoverer_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.7)',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_hoverer_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_hoverer_title_heading',
            [
                'label'     => esc_html__( 'Title Typography', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_fg_hoverer_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-item-card-hoverer .ua-fg-title, {{WRAPPER}} .ua-fg-item-card-hoverer .ua-fg-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_hoverer_title_typography',
                'selector' => '{{WRAPPER}} .ua-fg-item-card-hoverer .ua-fg-title',
            ]
        );

        $this->add_control(
            'ua_fg_hoverer_content_heading',
            [
                'label'     => esc_html__( 'Content Typography', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_fg_hoverer_content_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-item-card-hoverer .ua-fg-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_hoverer_content_typography',
                'selector' => '{{WRAPPER}} .ua-fg-item-card-hoverer .ua-fg-desc',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Item Card Style (Card & Search Filter).
     */
    protected function register_card_style_controls() {
        $this->start_controls_section(
            'section_fg_card_style_settings',
            [
                'label'     => esc_html__( 'Item Card', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_fg_caption_style' => [ 'card', 'layout_3' ],
                ],
            ]
        );

        $this->add_control(
            'ua_fg_card_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-card-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_card_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_card_title_heading',
            [
                'label'     => esc_html__( 'Title Typography', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_fg_card_title_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1a202c',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-card-body .ua-fg-title, {{WRAPPER}} .ua-fg-card-body .ua-fg-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_card_title_typography',
                'selector' => '{{WRAPPER}} .ua-fg-card-body .ua-fg-title',
            ]
        );

        $this->add_control(
            'ua_fg_card_content_heading',
            [
                'label'     => esc_html__( 'Content Typography', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_fg_card_content_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#718096',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-card-body .ua-fg-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_card_content_typography',
                'selector' => '{{WRAPPER}} .ua-fg-card-body .ua-fg-desc',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Video Styles.
     */
    protected function register_video_style_controls() {
        $this->start_controls_section(
            'section_fg_video_style_settings',
            [
                'label' => esc_html__( 'Video', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_fg_video_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 80,
                    ],
                ],
                'default'    => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-video-badge'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-fg-video-badge i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_video_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 4,
                        'max' => 60,
                    ],
                ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-video-badge' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_video_icon_color',
            [
                'label'     => esc_html__( 'Play Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-video-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_video_icon_bg',
            [
                'label'     => esc_html__( 'Play Icon Background', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.72)',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-video-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Action Icons Style.
     */
    protected function register_icon_style_controls() {
        $this->start_controls_section(
            'section_fg_icon_style_settings',
            [
                'label' => esc_html__( 'Icons', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_fg_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 80,
                    ],
                ],
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn'         => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-fg-action-btn i, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn i'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-fg-action-btn svg, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 4,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn' => 'padding: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_icon_gap',
            [
                'label'      => esc_html__( 'Spacing Between Buttons', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'default'    => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-actions' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_fg_icons_tabs' );

        // Normal
        $this->start_controls_tab(
            'ua_fg_icon_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_fg_icon_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3182ce',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_icon_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-fg-action-btn svg, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_icon_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'ua_fg_icon_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_fg_icon_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2b6cb0',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-action-btn:hover, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_icon_hover_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-action-btn:hover, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-fg-action-btn:hover svg, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_icon_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-action-btn:hover, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'ua_fg_icon_border',
                'separator' => 'before',
                'selector'  => '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn',
            ]
        );

        $this->add_responsive_control(
            'ua_fg_icon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-action-btn, {{WRAPPER}} .ua-fg-card-body .ua-fg-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Price Style.
     */
    protected function register_price_style_controls() {
        $this->start_controls_section(
            'section_fg_price_style_settings',
            [
                'label'     => esc_html__( 'Price', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_fg_caption_style' => 'layout_3',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_price_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#38a169',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_price_typography',
                'selector' => '{{WRAPPER}} .ua-fg-price',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Ratings Style.
     */
    protected function register_ratings_style_controls() {
        $this->start_controls_section(
            'section_fg_ratings_style_settings',
            [
                'label'     => esc_html__( 'Ratings', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_fg_caption_style' => 'layout_3',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_ratings_star_color',
            [
                'label'     => esc_html__( 'Star Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ecc94b',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-rating i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Category Badge Style.
     */
    protected function register_category_style_controls() {
        $this->start_controls_section(
            'section_fg_category_style_settings',
            [
                'label' => esc_html__( 'Category', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_fg_badge_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-category-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Search Form.
     */
    protected function register_search_form_style_controls() {
        $this->start_controls_section(
            'section_fg_search_form_style_settings',
            [
                'label'     => esc_html__( 'Search Form', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ua_fg_caption_style' => 'layout_3',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_sf_controls_color',
            [
                'label'     => esc_html__( 'Dropdown Trigger Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4a5568',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-filter-trigger' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Not Found Text Style.
     */
    protected function register_not_found_style_controls() {
        $this->start_controls_section(
            'section_fg_not_found_style_settings',
            [
                'label' => esc_html__( 'Not Found Text', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_fg_not_found_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#858e9a',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-not-found-msg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab -> Load More Button Styles.
     */
    protected function register_load_more_style_controls() {
        $this->start_controls_section(
            'section_fg_load_more_btn_style_settings',
            [
                'label'     => esc_html__( 'Load More Button', 'ultraaddons-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_fg_load_more_typography',
                'selector' => '{{WRAPPER}} .ua-fg-load-more-btn',
            ]
        );

        $this->start_controls_tabs( 'ua_fg_load_more_tabs' );

        // Normal State Tab
        $this->start_controls_tab(
            'ua_fg_load_more_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_fg_load_more_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-load-more-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_load_more_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3182ce',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-load-more-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_load_more_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-load-more-btn',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'ua_fg_load_more_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_fg_load_more_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-load-more-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_load_more_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2b6cb0',
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-load-more-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_fg_load_more_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-fg-load-more-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_fg_load_more_hover_shadow',
                'selector' => '{{WRAPPER}} .ua-fg-load-more-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'ua_fg_load_more_border',
                'separator' => 'before',
                'selector'  => '{{WRAPPER}} .ua-fg-load-more-btn',
            ]
        );

        $this->add_responsive_control(
            'ua_fg_load_more_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_fg_load_more_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-fg-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Slugify helper.
     */
    public static function slugify_category( $category ) {
        $slug = sanitize_title_with_dashes( trim( $category ) );
        return ! empty( $slug ) ? 'ua-fg-cat-' . $slug : '';
    }

    /**
     * Extract YouTube / Vimeo Video Thumbnail URL.
     */
    public static function get_video_thumbnail_url( $video_url ) {
        if ( empty( $video_url ) ) {
            return '';
        }

        // YouTube Shorts
        if ( preg_match( '/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i', $video_url, $m ) ) {
            return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
        }

        // YouTube standard, youtu.be, embed, nocookie
        if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $m ) ) {
            return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
        }

        // Vimeo
        if ( preg_match( '/\/\/(?:player\.)?vimeo.com\/(?:video\/)?([0-9]+)/', $video_url, $m ) ) {
            return 'https://vumbnail.com/' . $m[1] . '.jpg';
        }

        return '';
    }

    /**
     * Build normalized gallery items list.
     */
    protected function get_normalized_gallery_items( $settings ) {
        $raw_items = isset( $settings['ua_fg_gallery_items'] ) && is_array( $settings['ua_fg_gallery_items'] )
            ? $settings['ua_fg_gallery_items']
            : [];

        // Fallback default items if repeater is empty
        if ( empty( $raw_items ) ) {
            $raw_items = [
                [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
                [ 'ua_fg_gallery_item_name' => 'Gallery Item Name' ],
            ];
        }

        $items = [];
        $yt_privacy = ( 'yes' === ( $settings['video_gallery_yt_privacy'] ?? '' ) );

        foreach ( $raw_items as $index => $item ) {
            $categories_raw = isset( $item['ua_fg_gallery_control_name'] ) ? (string) $item['ua_fg_gallery_control_name'] : '';
            $cat_tokens     = array_filter( array_map( 'trim', explode( ',', $categories_raw ) ) );

            $cat_classes = [];
            foreach ( $cat_tokens as $cat ) {
                $slug = self::slugify_category( $cat );
                if ( $slug ) {
                    $cat_classes[] = $slug;
                }
            }

            $is_video     = ( isset( $item['fg_video_gallery_switch'] ) && 'true' === $item['fg_video_gallery_switch'] );
            $video_url    = ! empty( $item['ua_fg_gallery_item_video_link'] ) ? esc_url( $item['ua_fg_gallery_item_video_link'] ) : '';
            $video_layout = ! empty( $item['ua_fg_gallery_video_layout'] ) ? $item['ua_fg_gallery_video_layout'] : 'horizontal';

            if ( $is_video && $yt_privacy && ! empty( $video_url ) ) {
                if ( strpos( $video_url, 'youtube' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
                    $video_url = str_replace( [ 'youtube.com', 'youtu.be' ], 'youtube-nocookie.com', $video_url );
                }
                if ( strpos( $video_url, 'vimeo' ) !== false ) {
                    $video_url = add_query_arg( [ 'dnt' => 1 ], $video_url );
                }
            }

            // Image resolution handling
            $image_url   = '';
            $image_html  = '';
            $full_url    = '';
            $video_thumb = $is_video ? self::get_video_thumbnail_url( $video_url ) : '';

            if ( $is_video && ! empty( $video_thumb ) ) {
                // If it's a video, use the YouTube/Vimeo HD thumbnail
                $image_url  = $video_thumb;
                $full_url   = $video_thumb;
                $alt        = esc_attr( ! empty( $item['ua_fg_gallery_item_name'] ) ? $item['ua_fg_gallery_item_name'] : 'Video' );
                $image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . $alt . '" class="ua-fg-img" loading="lazy" />';
            } elseif ( ! empty( $item['ua_fg_gallery_img']['url'] ) ) {
                $image_url = $item['ua_fg_gallery_img']['url'];
                $full_url  = $image_url;

                if ( ! empty( $item['ua_fg_gallery_img']['id'] ) ) {
                    $full_img_src = wp_get_attachment_image_src( $item['ua_fg_gallery_img']['id'], 'full' );
                    if ( $full_img_src ) {
                        $full_url = $full_img_src[0];
                    }

                    $image_html = Group_Control_Image_Size::get_attachment_image_html( [
                        'ua_fg_image_resolution_size' => isset($settings['ua_fg_image_resolution_size']) ? $settings['ua_fg_image_resolution_size'] : 'medium',
                        'ua_fg_image_resolution_custom_dimension' => isset( $settings['ua_fg_image_resolution_custom_dimension'] ) ? $settings['ua_fg_image_resolution_custom_dimension'] : [],
                        'image_temp' => [
                            'id' => $item['ua_fg_gallery_img']['id'],
                        ],
                    ], 'image_temp' );
                }

                if ( empty( $image_html ) ) {
                    $alt = esc_attr( ! empty( $item['ua_fg_gallery_item_name'] ) ? $item['ua_fg_gallery_item_name'] : 'Gallery Image' );
                    $image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . $alt . '" class="ua-fg-img" loading="lazy" />';
                }
            } else {
                $image_url  = Utils::get_placeholder_image_src();
                $full_url   = $image_url;
                $image_html = '<div class="ua-fg-placeholder-img"><svg viewBox="0 0 24 24" width="48" height="48" fill="#a0aec0"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg></div>';
            }

            // Link URL handling
            $link_url    = ! empty( $item['ua_fg_gallery_img_link']['url'] ) ? $item['ua_fg_gallery_img_link']['url'] : '#';
            $is_external = ! empty( $item['ua_fg_gallery_img_link']['is_external'] ) ? '_blank' : '_self';
            $nofollow    = ! empty( $item['ua_fg_gallery_img_link']['nofollow'] ) ? 'nofollow' : '';

            $items[] = [
                'index'          => $index,
                'title'          => isset( $item['ua_fg_gallery_item_name'] ) ? $item['ua_fg_gallery_item_name'] : 'Gallery Item Name',
                'categories_raw' => $categories_raw,
                'cat_classes'    => implode( ' ', $cat_classes ),
                'image_url'      => $image_url,
                'full_url'       => $full_url,
                'image_html'     => $image_html,
                'content'        => isset( $item['ua_fg_gallery_item_content'] ) ? $item['ua_fg_gallery_item_content'] : 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
                'show_lightbox'  => ( ! isset( $item['ua_fg_gallery_lightbox'] ) || 'true' === $item['ua_fg_gallery_lightbox'] ),
                'show_link'      => ( ! isset( $item['ua_fg_gallery_link'] ) || 'true' === $item['ua_fg_gallery_link'] ),
                'link_url'       => $link_url,
                'is_external'    => $is_external,
                'nofollow'       => $nofollow,
                'is_video'       => $is_video,
                'video_url'      => $video_url,
                'video_layout'   => $video_layout,
                'show_price'     => ( isset( $item['fg_item_price_switch'] ) && 'true' === $item['fg_item_price_switch'] ),
                'price'          => isset( $item['fg_item_price'] ) ? $item['fg_item_price'] : '',
                'show_rating'    => ( isset( $item['fg_item_ratings_switch'] ) && 'true' === $item['fg_item_ratings_switch'] ),
                'rating'         => isset( $item['fg_item_ratings'] ) ? floatval( $item['fg_item_ratings'] ) : 5,
                'show_category'  => ( isset( $item['fg_item_cat_switch'] ) && 'true' === $item['fg_item_cat_switch'] ),
                'category_badge' => isset( $item['fg_item_cat'] ) ? $item['fg_item_cat'] : '',
            ];
        }

        if ( 'yes' === ( $settings['ua_item_randomize'] ?? '' ) ) {
            shuffle( $items );
        }

        return $items;
    }

    /**
     * Render single gallery item markup.
     */
    public function render_single_item_html( $item, $settings ) {
        $layout          = $settings['ua_fg_caption_style'] ?? 'hoverer';
        $hover_anim      = $settings['ua_fg_grid_hover_style'] ?? 'slide-up';
        $link_to         = $settings['ua_fg_show_popup'] ?? 'buttons';
        $media_action    = $settings['ua_section_fg_full_image_action'] ?? 'lightbox';
        $title_tag       = ! empty( $settings['title_tag'] ) ? $settings['title_tag'] : 'h2';
        $title_clickable = ( 'yes' === ( $settings['ua_title_clickable'] ?? '' ) );
        $show_caption    = ( 'yes' === ( $settings['ua_section_fg_mfp_caption'] ?? '' ) );

        $popup_type = $item['is_video'] ? 'video' : 'image';
        $popup_src  = $item['is_video'] ? $item['video_url'] : $item['full_url'];

        $item_wrap_classes = [
            'ua-fg-item',
            'ua-fg-item-anim-' . esc_attr( $hover_anim ),
            $item['cat_classes'],
        ];

        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $item_wrap_classes ) ); ?>"
             data-categories="<?php echo esc_attr( strtolower( $item['categories_raw'] ) ); ?>"
             data-title="<?php echo esc_attr( strtolower( $item['title'] ) ); ?>">
            
            <div class="ua-fg-item-card ua-fg-item-card-<?php echo esc_attr( $layout ); ?>">
                
                <?php if ( 'media' === $link_to ) : ?>
                    <?php if ( 'lightbox' === $media_action || $item['is_video'] ) : ?>
                        <a href="<?php echo esc_url( $popup_src ); ?>"
                           class="ua-fg-media-link ua-fg-lightbox-trigger"
                           data-popup-type="<?php echo esc_attr( $popup_type ); ?>"
                           data-video-layout="<?php echo esc_attr( $item['video_layout'] ); ?>"
                           data-title="<?php echo $show_caption ? esc_attr( $item['title'] ) : ''; ?>"
                           aria-label="<?php echo esc_attr( $item['title'] ); ?>">
                    <?php else : ?>
                        <a href="<?php echo esc_url( $item['link_url'] ); ?>"
                           class="ua-fg-media-link"
                           target="<?php echo esc_attr( $item['is_external'] ); ?>"
                           <?php echo ! empty( $item['nofollow'] ) ? 'rel="nofollow"' : ''; ?>
                           aria-label="<?php echo esc_attr( $item['title'] ); ?>">
                    <?php endif; ?>
                <?php endif; ?>

                <div class="ua-fg-thumb-wrap">
                    <?php echo $item['image_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                    <?php if ( $item['is_video'] ) : ?>
                        <a href="<?php echo esc_url( $popup_src ); ?>"
                           class="ua-fg-video-badge ua-fg-lightbox-trigger"
                           data-popup-type="<?php echo esc_attr( $popup_type ); ?>"
                           data-video-layout="<?php echo esc_attr( $item['video_layout'] ); ?>"
                           data-title="<?php echo $show_caption ? esc_attr( $item['title'] ) : ''; ?>"
                           aria-label="<?php echo esc_attr__( 'Play Video', 'ultraaddons-elementor-lite' ); ?>">
                            <i class="fas fa-play" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ( $item['show_category'] && ! empty( $item['category_badge'] ) ) : ?>
                        <span class="ua-fg-category-badge"><?php echo esc_html( $item['category_badge'] ); ?></span>
                    <?php endif; ?>

                    <?php if ( 'hoverer' === $layout || 'card' === $layout ) : ?>
                        <div class="ua-fg-overlay">
                            <div class="ua-fg-overlay-content">
                                
                                <?php if ( 'hoverer' === $layout ) : ?>
                                    <?php if ( ! empty( $item['title'] ) ) : ?>
                                        <<?php echo esc_html( $title_tag ); ?> class="ua-fg-title">
                                            <?php if ( $title_clickable && ! empty( $item['link_url'] ) && '#' !== $item['link_url'] ) : ?>
                                                <a href="<?php echo esc_url( $item['link_url'] ); ?>" target="<?php echo esc_attr( $item['is_external'] ); ?>" <?php echo ! empty( $item['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
                                                    <?php echo esc_html( $item['title'] ); ?>
                                                </a>
                                            <?php else : ?>
                                                <?php echo esc_html( $item['title'] ); ?>
                                            <?php endif; ?>
                                        </<?php echo esc_html( $title_tag ); ?>>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $item['content'] ) ) : ?>
                                        <div class="ua-fg-desc"><?php echo wp_kses_post( $item['content'] ); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ( 'buttons' === $link_to ) : ?>
                                    <div class="ua-fg-actions">
                                        <?php if ( $item['show_lightbox'] || $item['is_video'] ) : ?>
                                            <a href="<?php echo esc_url( $popup_src ); ?>"
                                               class="ua-fg-action-btn ua-fg-lightbox-trigger <?php echo $item['is_video'] ? 'ua-fg-video-action-btn' : ''; ?>"
                                               data-popup-type="<?php echo esc_attr( $popup_type ); ?>"
                                               data-video-layout="<?php echo esc_attr( $item['video_layout'] ); ?>"
                                               data-title="<?php echo $show_caption ? esc_attr( $item['title'] ) : ''; ?>"
                                               title="<?php echo $item['is_video'] ? esc_attr__( 'Play Video', 'ultraaddons-elementor-lite' ) : esc_attr__( 'Preview', 'ultraaddons-elementor-lite' ); ?>"
                                               aria-label="<?php echo $item['is_video'] ? esc_attr__( 'Play Video', 'ultraaddons-elementor-lite' ) : esc_attr__( 'Preview in Lightbox', 'ultraaddons-elementor-lite' ); ?>">
                                                <?php if ( $item['is_video'] ) : ?>
                                                    <i class="fas fa-play" aria-hidden="true"></i>
                                                <?php else : ?>
                                                    <?php Icons_Manager::render_icon( $settings['ua_section_fg_zoom_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
                                                <?php endif; ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ( $item['show_link'] && ! empty( $item['link_url'] ) && '#' !== $item['link_url'] ) : ?>
                                            <a href="<?php echo esc_url( $item['link_url'] ); ?>"
                                               class="ua-fg-action-btn ua-fg-link-btn"
                                               target="<?php echo esc_attr( $item['is_external'] ); ?>"
                                               <?php echo ! empty( $item['nofollow'] ) ? 'rel="nofollow"' : ''; ?>
                                               title="<?php echo esc_attr__( 'Open Link', 'ultraaddons-elementor-lite' ); ?>"
                                               aria-label="<?php echo esc_attr__( 'Open Link', 'ultraaddons-elementor-lite' ); ?>">
                                                <?php Icons_Manager::render_icon( $settings['ua_section_fg_link_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ( 'media' === $link_to ) : ?>
                    </a>
                <?php endif; ?>

                <?php if ( 'card' === $layout || 'layout_3' === $layout ) : ?>
                    <div class="ua-fg-card-body">
                        
                        <div class="ua-fg-card-meta">
                            <?php if ( $item['show_price'] && ! empty( $item['price'] ) ) : ?>
                                <span class="ua-fg-price"><?php echo esc_html( $item['price'] ); ?></span>
                            <?php endif; ?>

                            <?php if ( $item['show_rating'] ) : ?>
                                <div class="ua-fg-rating" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'ultraaddons-elementor-lite' ), $item['rating'] ) ); ?>">
                                    <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                                        <i class="<?php echo ( $s <= $item['rating'] ) ? 'fas fa-star' : 'far fa-star'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ( ! empty( $item['title'] ) ) : ?>
                            <<?php echo esc_html( $title_tag ); ?> class="ua-fg-title">
                                <?php if ( $title_clickable && ! empty( $item['link_url'] ) && '#' !== $item['link_url'] ) : ?>
                                    <a href="<?php echo esc_url( $item['link_url'] ); ?>" target="<?php echo esc_attr( $item['is_external'] ); ?>" <?php echo ! empty( $item['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
                                        <?php echo esc_html( $item['title'] ); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo esc_html( $item['title'] ); ?>
                                <?php endif; ?>
                            </<?php echo esc_html( $title_tag ); ?>>
                        <?php endif; ?>

                        <?php if ( ! empty( $item['content'] ) ) : ?>
                            <div class="ua-fg-desc"><?php echo wp_kses_post( $item['content'] ); ?></div>
                        <?php endif; ?>

                        <?php if ( 'layout_3' === $layout && 'buttons' === $link_to ) : ?>
                            <div class="ua-fg-actions ua-fg-actions-bottom">
                                <?php if ( $item['show_lightbox'] || $item['is_video'] ) : ?>
                                    <a href="<?php echo esc_url( $popup_src ); ?>"
                                       class="ua-fg-action-btn ua-fg-lightbox-trigger"
                                       data-popup-type="<?php echo esc_attr( $popup_type ); ?>"
                                       data-video-layout="<?php echo esc_attr( $item['video_layout'] ); ?>"
                                       data-title="<?php echo $show_caption ? esc_attr( $item['title'] ) : ''; ?>"
                                       title="<?php echo esc_attr__( 'Preview', 'ultraaddons-elementor-lite' ); ?>"
                                       aria-label="<?php echo esc_attr__( 'Preview in Lightbox', 'ultraaddons-elementor-lite' ); ?>">
                                        <?php Icons_Manager::render_icon( $settings['ua_section_fg_zoom_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $item['show_link'] && ! empty( $item['link_url'] ) && '#' !== $item['link_url'] ) : ?>
                                    <a href="<?php echo esc_url( $item['link_url'] ); ?>"
                                       class="ua-fg-action-btn ua-fg-link-btn"
                                       target="<?php echo esc_attr( $item['is_external'] ); ?>"
                                       <?php echo ! empty( $item['nofollow'] ) ? 'rel="nofollow"' : ''; ?>
                                       title="<?php echo esc_attr__( 'Open Link', 'ultraaddons-elementor-lite' ); ?>"
                                       aria-label="<?php echo esc_attr__( 'Open Link', 'ultraaddons-elementor-lite' ); ?>">
                                        <?php Icons_Manager::render_icon( $settings['ua_section_fg_link_icon_new'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Standard Filter Bar.
     */
    protected function render_filter_bar( $settings ) {
        if ( 'yes' !== ( $settings['filter_enable'] ?? 'yes' ) ) {
            return;
        }

        $all_label = ! empty( $settings['ua_fg_all_label_text'] ) ? $settings['ua_fg_all_label_text'] : esc_html__( 'All', 'ultraaddons-elementor-lite' );
        $controls  = isset( $settings['ua_fg_controls'] ) && is_array( $settings['ua_fg_controls'] )
            ? $settings['ua_fg_controls']
            : [];
        $has_custom_default = false;
        foreach ( $controls as $ctrl ) {
            if ( ! empty( $ctrl['ua_fg_control_active_as_default'] ) && 'yes' === $ctrl['ua_fg_control_active_as_default'] ) {
                $has_custom_default = true;
                break;
            }
        }
        ?>
        <div class="ua-fg-controls-wrap">
            <ul class="ua-fg-controls" role="tablist">
                
                <?php if ( ! empty( $all_label ) ) : ?>
                    <li class="ua-fg-control-item" role="presentation">
                        <button type="button"
                                class="ua-fg-filter-btn <?php echo ( ! $has_custom_default ) ? 'ua-fg-active' : ''; ?>"
                                data-filter="*"
                                role="tab"
                                aria-selected="<?php echo ( ! $has_custom_default ) ? 'true' : 'false'; ?>">
                            <?php echo esc_html( $all_label ); ?>
                        </button>
                    </li>
                <?php endif; ?>

                <?php foreach ( $controls as $ctrl ) :
                    $title      = ! empty( $ctrl['ua_fg_control'] ) ? $ctrl['ua_fg_control'] : '';
                    $slug       = self::slugify_category( $title );
                    $custom_id  = ! empty( $ctrl['ua_fg_control_custom_id'] ) ? sanitize_title( $ctrl['ua_fg_control_custom_id'] ) : '';
                    $is_default = ( ! empty( $ctrl['ua_fg_control_active_as_default'] ) && 'yes' === $ctrl['ua_fg_control_active_as_default'] );
                    if ( empty( $title ) ) continue;
                ?>
                    <li class="ua-fg-control-item" role="presentation">
                        <button type="button"
                                class="ua-fg-filter-btn <?php echo $is_default ? 'ua-fg-active' : ''; ?>"
                                data-filter=".<?php echo esc_attr( $slug ); ?>"
                                <?php echo ! empty( $custom_id ) ? 'id="' . esc_attr( $custom_id ) . '"' : ''; ?>
                                role="tab"
                                aria-selected="<?php echo $is_default ? 'true' : 'false'; ?>">
                            <?php echo esc_html( $title ); ?>
                        </button>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
        <?php
    }

    /**
     * Render Layout 3 (Search & Filter: Dropdown Menu + Search Form).
     */
    protected function render_search_and_filter_bar( $settings ) {
        $all_label = ! empty( $settings['ua_fg_all_label_text'] ) ? $settings['ua_fg_all_label_text'] : esc_html__( 'All', 'ultraaddons-elementor-lite' );
        $controls  = isset( $settings['ua_fg_controls'] ) && is_array( $settings['ua_fg_controls'] )
            ? $settings['ua_fg_controls']
            : [];

        $active_label = $all_label;
        foreach ( $controls as $ctrl ) {
            if ( ! empty( $ctrl['ua_fg_control_active_as_default'] ) && 'yes' === $ctrl['ua_fg_control_active_as_default'] ) {
                $active_label = $ctrl['ua_fg_control'];
                break;
            }
        }
        ?>
        <div class="ua-fg-top-bar ua-fg-layout-3-top-bar">
            <div class="ua-fg-search-form-wrap">
                
                <?php if ( 'yes' === ( $settings['filter_enable'] ?? 'yes' ) ) : ?>
                    <div class="ua-fg-dropdown-filter-wrap">
                        <button type="button" class="ua-fg-filter-trigger" aria-haspopup="true" aria-expanded="false">
                            <span class="ua-fg-trigger-text"><?php echo esc_html( $active_label ); ?></span>
                            <?php if ( ! empty( $settings['fg_all_label_icon']['value'] ) ) : ?>
                                <span class="ua-fg-trigger-icon"><?php Icons_Manager::render_icon( $settings['fg_all_label_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                            <?php endif; ?>
                        </button>

                        <ul class="ua-fg-dropdown-menu" role="menu">
                            <?php if ( ! empty( $all_label ) ) : ?>
                                <li class="ua-fg-dropdown-item <?php echo ( $active_label === $all_label ) ? 'ua-fg-active' : ''; ?>" data-filter="*" role="menuitem">
                                    <?php echo esc_html( $all_label ); ?>
                                </li>
                            <?php endif; ?>

                            <?php foreach ( $controls as $ctrl ) :
                                $title     = ! empty( $ctrl['ua_fg_control'] ) ? $ctrl['ua_fg_control'] : '';
                                $slug      = self::slugify_category( $title );
                                $custom_id = ! empty( $ctrl['ua_fg_control_custom_id'] ) ? sanitize_title( $ctrl['ua_fg_control_custom_id'] ) : '';
                                $is_active = ( $active_label === $title );
                                if ( empty( $title ) ) continue;
                            ?>
                                <li class="ua-fg-dropdown-item <?php echo $is_active ? 'ua-fg-active' : ''; ?>"
                                    data-filter=".<?php echo esc_attr( $slug ); ?>"
                                    <?php echo ! empty( $custom_id ) ? 'id="' . esc_attr( $custom_id ) . '"' : ''; ?>
                                    role="menuitem">
                                    <?php echo esc_html( $title ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="ua-fg-search-box">
                    <input type="text"
                           class="ua-fg-search-input"
                           placeholder="<?php echo esc_attr__( 'Search gallery items...', 'ultraaddons-elementor-lite' ); ?>"
                           aria-label="<?php echo esc_attr__( 'Search gallery items', 'ultraaddons-elementor-lite' ); ?>" />
                    <i class="fas fa-search ua-fg-search-icon" aria-hidden="true"></i>
                </div>

            </div>
        </div>
        <?php
    }

    /**
     * Render the widget on frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = $this->get_normalized_gallery_items( $settings );

        $total_items    = count( $items );
        $items_to_show  = ! empty( $settings['ua_fg_items_to_show'] ) ? absint( $settings['ua_fg_items_to_show'] ) : 6;
        $grid_style     = $settings['ua_fg_grid_style'] ?? 'grid';
        $layout         = $settings['ua_fg_caption_style'] ?? 'hoverer';
        $duration       = ! empty( $settings['ua_fg_filter_duration'] ) ? absint( $settings['ua_fg_filter_duration'] ) : 500;
        $mobile_scroll  = ( 'yes' === ( $settings['ua_fg_mobile_scroll_to_top'] ?? '' ) );
        $scroll_offset  = ! empty( $settings['ua_fg_mobile_scroll_offset'] ) ? intval( $settings['ua_fg_mobile_scroll_offset'] ) : 0;
        $is_pagination  = ( 'yes' === ( $settings['pagination'] ?? '' ) );

        // Render HTML array of all items
        $all_items_html = [];
        foreach ( $items as $item ) {
            $all_items_html[] = $this->render_single_item_html( $item, $settings );
        }

        // Prepare config for frontend JS
        $js_config = [
            'gridStyle'     => $grid_style,
            'duration'      => $duration,
            'itemsToShow'   => $items_to_show,
            'totalItems'    => $total_items,
            'searchAll'     => ( 'yes' === ( $settings['ua_search_among_all'] ?? '' ) ),
            'mobileScroll'  => $mobile_scroll,
            'scrollOffset'  => $scroll_offset,
        ];

        // Base64 encode full items HTML dataset for client-side pagination / load more
        $encoded_items = base64_encode( wp_json_encode( $all_items_html ) );

        $wrapper_classes = [
            'ua-filterable-gallery-wrap',
            'ua-fg-wrapper',
            'ua-fg-layout-' . esc_attr( $layout ),
            'ua-fg-style-' . esc_attr( $grid_style ),
        ];

        $columns_desktop = ! empty( $settings['columns'] ) ? $settings['columns'] : '3';
        $columns_tablet  = ! empty( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : '2';
        $columns_mobile  = ! empty( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : '1';

        $container_classes = [
            'ua-fg-container',
            'ua-fg-cols-' . esc_attr( $columns_desktop ),
            'ua-fg-cols-tab-' . esc_attr( $columns_tablet ),
            'ua-fg-cols-mob-' . esc_attr( $columns_mobile ),
        ];
        ?>
        <div id="ua-fg-<?php echo esc_attr( $this->get_id() ); ?>"
             class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
             data-config="<?php echo esc_attr( wp_json_encode( $js_config ) ); ?>">

            <?php
            // Render Filters Header
            if ( 'layout_3' === $layout ) {
                $this->render_search_and_filter_bar( $settings );
            } else {
                $this->render_filter_bar( $settings );
            }
            ?>

            <div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"
                 data-items-dataset="<?php echo esc_attr( $encoded_items ); ?>">
                
                <?php
                // Render initial batch of items
                $initial_limit = $is_pagination ? min( $items_to_show, $total_items ) : $total_items;
                for ( $i = 0; $i < $initial_limit; $i++ ) {
                    if ( isset( $all_items_html[ $i ] ) ) {
                        echo $all_items_html[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                }
                ?>

            </div>

            <div class="ua-fg-not-found-msg" style="display:none;">
                <p><?php echo esc_html( ! empty( $settings['ua_fg_not_found_text'] ) ? $settings['ua_fg_not_found_text'] : __( 'No Items Found', 'ultraaddons-elementor-lite' ) ); ?></p>
            </div>

            <?php if ( $is_pagination && $total_items > $items_to_show ) : ?>
                <div class="ua-fg-load-more-wrap">
                    <button type="button" class="ua-fg-load-more-btn elementor-button">
                        <span class="ua-fg-btn-text"><?php echo esc_html( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : __( 'Load More', 'ultraaddons-elementor-lite' ) ); ?></span>
                    </button>
                    <span class="ua-fg-no-more-msg" style="display:none;"><?php echo esc_html( ! empty( $settings['nomore_items_text'] ) ? $settings['nomore_items_text'] : __( 'No more items!', 'ultraaddons-elementor-lite' ) ); ?></span>
                </div>
            <?php endif; ?>

        </div>
        <?php
    }
}
