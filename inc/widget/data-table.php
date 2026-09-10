<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * UltraAddons - Data Table Widget
 *
 * A modern, versatile, and conversion-ready Data Table widget for Elementor.
 * Features:
 * - Repeater-based Row & Column Builder with dynamic tags
 * - Multi-type cell contents: Text, Rich WYSIWYG Editor, Icons, and Saved Elementor Templates
 * - Cell spanning (Colspan & Rowspan)
 * - Interactive frontend column sorting (numbers, strings, currency, dates)
 * - Frontend and editor CSV export capabilities
 * - Zebra striping with individual Odd/Even row and cell styling
 * - Mobile responsiveness: Card/Stack view and Smooth Horizontal Scroll view
 *
 * @package UltraAddons
 * @version 2.0.4
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Data_Table extends Base {

    /**
     * Constructor: Register widget styles and scripts.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $css_file = ULTRA_ADDONS_DIR . 'assets/css/widgets/data-table.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ULTRA_ADDONS_VERSION;

        wp_register_style(
            'ultraaddons-data-table',
            ULTRA_ADDONS_ASSETS . 'css/widgets/data-table.css',
            [],
            $css_ver,
            'all'
        );

        $js_file = ULTRA_ADDONS_DIR . 'assets/js/frontend-data-table.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ULTRA_ADDONS_VERSION;

        wp_register_script(
            'ultraaddons-data-table-js',
            ULTRA_ADDONS_ASSETS . 'js/frontend-data-table.js',
            [ 'jquery' ],
            $js_ver,
            true
        );
    }

    /**
     * Widget Name identifier.
     */
    public function get_name() {
        return 'ultraaddons-data-table';
    }

    /**
     * Widget Title.
     */
    public function get_title() {
        return esc_html__( 'Data Table', 'ultraaddons-elementor-lite' );
    }

    /**
     * Widget Icon.
     */
    public function get_icon() {
        return 'ultraaddons eicon-table';
    }

    /**
     * Widget Keywords.
     */
    public function get_keywords() {
        return [ 'table', 'data table', 'comparison table', 'grid', 'specs', 'specification', 'pricing table', 'ultraaddons' ];
    }

    /**
     * Style Dependencies.
     */
    public function get_style_depends() {
        return array_merge( parent::get_style_depends(), [
            'ultraaddons-data-table',
            'elementor-icons-fa-solid',
            'elementor-icons-fa-regular',
        ] );
    }

    /**
     * Script Dependencies.
     */
    public function get_script_depends() {
        return array_merge( parent::get_script_depends(), [
            'ultraaddons-data-table-js',
        ] );
    }

    /**
     * Retrieve saved Elementor templates.
     *
     * @return array
     */
    public static function get_elementor_templates() {
        $templates = [
            '' => esc_html__( '— Select Template —', 'ultraaddons-elementor-lite' ),
        ];

        $posts = get_posts( [
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {
            foreach ( $posts as $post ) {
                $templates[ $post->ID ] = $post->post_title;
            }
        }

        return $templates;
    }

    /**
     * Render Elementor Template content by ID.
     *
     * @param int|string $id
     * @return string
     */
    public function render_template_content( $id ) {
        if ( empty( $id ) ) {
            return '';
        }

        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $default_language_code = apply_filters( 'wpml_default_language', null );
            if ( ICL_LANGUAGE_CODE !== $default_language_code ) {
                $id = apply_filters( 'wpml_object_id', $id, 'elementor_library', true );
            }
        }

        $frontend = \Elementor\Plugin::instance()->frontend;
        if ( method_exists( $frontend, 'get_builder_content_for_display' ) ) {
            return $frontend->get_builder_content_for_display( $id );
        }

        return '';
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Content Tab Controls.
     */
    protected function register_content_controls() {

        /**
         * ----------------------------------------------------
         * Content Section: Table Header
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_section_header',
            [
                'label' => esc_html__( 'Table Header', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_dt_enable_sorting',
            [
                'label'        => esc_html__( 'Enable Column Sorting', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Allows visitors to sort table rows by clicking column headers.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $header_repeater = new Repeater();

        $header_repeater->add_control(
            'ua_dt_col_name',
            [
                'label'       => esc_html__( 'Column Title', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Column', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_span',
            [
                'label'       => esc_html__( 'Col Span', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1,
                'min'         => 1,
                'max'         => 20,
                'description' => esc_html__( 'Number of columns this header cell spans across.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_media_type',
            [
                'label'   => esc_html__( 'Media Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'none'  => [
                        'title' => esc_html__( 'None', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-image-bold',
                    ],
                ],
                'default' => 'none',
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_icon',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_dt_col_media_type' => 'icon',
                ],
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_image',
            [
                'label'     => esc_html__( 'Image', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'ua_dt_col_media_type' => 'image',
                ],
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_image_size',
            [
                'label'     => esc_html__( 'Image Size (px)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 24,
                'min'       => 10,
                'max'       => 120,
                'condition' => [
                    'ua_dt_col_media_type' => 'image',
                ],
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_class',
            [
                'label'       => esc_html__( 'CSS Class', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => false,
            ]
        );

        $header_repeater->add_control(
            'ua_dt_col_id',
            [
                'label'       => esc_html__( 'CSS ID', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => false,
            ]
        );

        $this->add_control(
            'ua_dt_header_cols',
            [
                'label'       => esc_html__( 'Columns', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $header_repeater->get_controls(),
                'title_field' => '{{{ ua_dt_col_name }}}',
                'default'     => [
                    [
                        'ua_dt_col_name' => esc_html__( 'Features & Specs', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_dt_col_name' => esc_html__( 'Starter Plan', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_dt_col_name' => esc_html__( 'Professional', 'ultraaddons-elementor-lite' ),
                    ],
                    [
                        'ua_dt_col_name' => esc_html__( 'Enterprise', 'ultraaddons-elementor-lite' ),
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * ----------------------------------------------------
         * Content Section: Table Content (Rows & Cells)
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_section_content',
            [
                'label' => esc_html__( 'Table Rows & Content', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $row_repeater = new Repeater();

        $row_repeater->add_control(
            'ua_dt_row_type',
            [
                'label'   => esc_html__( 'Row / Column Type', 'ultraaddons-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__( 'New Row (<tr>)', 'ultraaddons-elementor-lite' ),
                    'col' => esc_html__( 'Cell / Column (<td>)', 'ultraaddons-elementor-lite' ),
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_colspan',
            [
                'label'       => esc_html__( 'Col Span', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1,
                'min'         => 1,
                'max'         => 20,
                'condition'   => [
                    'ua_dt_row_type' => 'col',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_rowspan',
            [
                'label'       => esc_html__( 'Row Span', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1,
                'min'         => 1,
                'max'         => 20,
                'condition'   => [
                    'ua_dt_row_type' => 'col',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_content_type',
            [
                'label'     => esc_html__( 'Content Type', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'textarea' => [
                        'title' => esc_html__( 'Text / HTML', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-area',
                    ],
                    'editor'   => [
                        'title' => esc_html__( 'Rich Editor', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-pencil',
                    ],
                    'icon'     => [
                        'title' => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-star',
                    ],
                    'template' => [
                        'title' => esc_html__( 'Saved Template', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-document-file',
                    ],
                ],
                'default'   => 'textarea',
                'condition' => [
                    'ua_dt_row_type' => 'col',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_text',
            [
                'label'       => esc_html__( 'Cell Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__( 'Cell Content', 'ultraaddons-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'rows'        => 3,
                'condition'   => [
                    'ua_dt_row_type'         => 'col',
                    'ua_dt_cell_content_type' => 'textarea',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_editor',
            [
                'label'       => esc_html__( 'Rich Content', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__( 'Content with <strong>formatting</strong>', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_dt_row_type'         => 'col',
                    'ua_dt_cell_content_type' => 'editor',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_icon',
            [
                'label'     => esc_html__( 'Icon', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-check-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'ua_dt_row_type'         => 'col',
                    'ua_dt_cell_content_type' => 'icon',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_template',
            [
                'label'       => esc_html__( 'Select Template', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => self::get_elementor_templates(),
                'condition'   => [
                    'ua_dt_row_type'         => 'col',
                    'ua_dt_cell_content_type' => 'template',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_link',
            [
                'label'       => esc_html__( 'Link URL', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_dt_row_type' => 'col',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_class',
            [
                'label'       => esc_html__( 'CSS Class', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_dt_row_type' => 'col',
                ],
            ]
        );

        $row_repeater->add_control(
            'ua_dt_cell_id',
            [
                'label'       => esc_html__( 'CSS ID', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'condition'   => [
                    'ua_dt_row_type' => 'col',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_rows',
            [
                'label'       => esc_html__( 'Rows & Columns', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $row_repeater->get_controls(),
                'title_field' => '<# if ( ua_dt_row_type === "row" ) { #>— [ New Row ] —<# } else { #>Cell: {{{ ua_dt_cell_text || ua_dt_cell_editor || "Item" }}}<# } #>',
                'default'     => [
                    // Row 1
                    [ 'ua_dt_row_type' => 'row' ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Cloud Storage', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( '10 GB SSD', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( '50 GB NVMe', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Unlimited NVMe', 'ultraaddons-elementor-lite' ) ],

                    // Row 2
                    [ 'ua_dt_row_type' => 'row' ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Active Websites', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( '1 Domain', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( '5 Domains', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Unlimited Domains', 'ultraaddons-elementor-lite' ) ],

                    // Row 3
                    [ 'ua_dt_row_type' => 'row' ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( '24/7 Priority Support', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Email Only', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Live Chat & Email', 'ultraaddons-elementor-lite' ) ],
                    [ 'ua_dt_row_type' => 'col', 'ua_dt_cell_text' => esc_html__( 'Dedicated VIP Phone', 'ultraaddons-elementor-lite' ) ],
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * ----------------------------------------------------
         * Content Section: Export Options
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_section_export',
            [
                'label' => esc_html__( 'CSV Export', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ua_dt_show_frontend_export',
            [
                'label'        => esc_html__( 'Frontend Download Button', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'Hide', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => esc_html__( 'Shows a download button on the live page so visitors can export table data to CSV.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_dt_frontend_export_text',
            [
                'label'       => esc_html__( 'Button Text', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Export Table (CSV)', 'ultraaddons-elementor-lite' ),
                'condition'   => [
                    'ua_dt_show_frontend_export' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style Tab Controls.
     */
    protected function register_style_controls() {

        /**
         * ----------------------------------------------------
         * Style Section: General Style
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_style_general',
            [
                'label' => esc_html__( 'General Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_dt_table_width',
            [
                'label'      => esc_html__( 'Table Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range'      => [
                    '%'  => [ 'min' => 20, 'max' => 100 ],
                    'px' => [ 'min' => 200, 'max' => 1600 ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-data-table-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_dt_table_align',
            [
                'label'     => esc_html__( 'Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'   => 'center',
                'prefix_class' => 'ua-table-align-',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'ua_dt_table_shadow',
                'selector' => '{{WRAPPER}} .ua-table-responsive-container',
            ]
        );

        $this->add_control(
            'ua_dt_table_border_radius',
            [
                'label'      => esc_html__( 'Container Border Radius', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-table-responsive-container' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * ----------------------------------------------------
         * Style Section: Header Style
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_style_header',
            [
                'label' => esc_html__( 'Header Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ua_dt_header_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => 14,
                    'right'    => 18,
                    'bottom'   => 14,
                    'left'     => 18,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-table-th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ua_dt_header_style_tabs' );

        // Normal State
        $this->start_controls_tab(
            'ua_dt_header_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_dt_header_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_header_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1e293b',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_dt_header_border',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-table-th',
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'ua_dt_header_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_dt_header_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-table-th:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_header_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-table-th:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'ua_dt_header_border_hover',
                'label'    => esc_html__( 'Border', 'ultraaddons-elementor-lite' ),
                'selector' => '{{WRAPPER}} .ua-table-th:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'ua_dt_header_typography',
                'selector'  => '{{WRAPPER}} .ua-table-th, {{WRAPPER}} .ua-header-text',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_dt_header_icon_size',
            [
                'label'      => esc_html__( 'Icon / Media Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default'    => [ 'size' => 18 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-table-th i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-table-th svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-table-th img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_dt_header_icon_space',
            [
                'label'      => esc_html__( 'Icon Spacing', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default'    => [ 'size' => 8 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-header-media' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_dt_header_align',
            [
                'label'     => esc_html__( 'Header Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-th' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * ----------------------------------------------------
         * Style Section: Content Style (Cells & Rows)
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_style_content',
            [
                'label' => esc_html__( 'Content Style', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'ua_dt_cell_style_tabs' );

        // Normal State
        $this->start_controls_tab(
            'ua_dt_cell_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_dt_odd_row_heading',
            [
                'label' => esc_html__( 'Odd Rows (Row 1, 3, 5...)', 'ultraaddons-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ua_dt_odd_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-row:nth-child(odd) .ua-table-td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_odd_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-row:nth-child(odd) .ua-table-td' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_even_row_heading',
            [
                'label'     => esc_html__( 'Even Rows (Zebra Stripes)', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ua_dt_even_color',
            [
                'label'     => esc_html__( 'Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#334155',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-row:nth-child(even) .ua-table-td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_even_bg',
            [
                'label'     => esc_html__( 'Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-row:nth-child(even) .ua-table-td' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'ua_dt_cell_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_dt_row_hover_color',
            [
                'label'     => esc_html__( 'Hover Text Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ua-table-row:hover .ua-table-td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_row_hover_bg',
            [
                'label'     => esc_html__( 'Hover Background Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1f5f9',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-row:hover .ua-table-td' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'ua_dt_cell_border',
                'label'     => esc_html__( 'Cell Border', 'ultraaddons-elementor-lite' ),
                'selector'  => '{{WRAPPER}} .ua-table-td',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_dt_cell_padding',
            [
                'label'      => esc_html__( 'Padding', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'      => 14,
                    'right'    => 18,
                    'bottom'   => 14,
                    'left'     => 18,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-table-td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ua_dt_cell_typography',
                'selector' => '{{WRAPPER}} .ua-table-td',
            ]
        );

        $this->add_responsive_control(
            'ua_dt_cell_alignment',
            [
                'label'     => esc_html__( 'Content Alignment', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'ultraaddons-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Link Color Heading & Tabs
        $this->add_control(
            'ua_dt_link_heading',
            [
                'label'     => esc_html__( 'Cell Link Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'ua_dt_link_tabs' );

        $this->start_controls_tab(
            'ua_dt_link_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_dt_link_color',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-td a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ua_dt_link_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'ultraaddons-elementor-lite' ) ]
        );

        $this->add_control(
            'ua_dt_link_color_hover',
            [
                'label'     => esc_html__( 'Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1d4ed8',
                'selectors' => [
                    '{{WRAPPER}} .ua-table-td a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Icon Styling inside Cell
        $this->add_control(
            'ua_dt_cell_icon_heading',
            [
                'label'     => esc_html__( 'Cell Icon Style', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ua_dt_cell_icon_size',
            [
                'label'      => esc_html__( 'Icon Size', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default'    => [ 'size' => 18 ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-cell-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ua-cell-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_cell_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .ua-cell-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ua-cell-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * ----------------------------------------------------
         * Style Section: Responsive Options
         * ----------------------------------------------------
         */
        $this->start_controls_section(
            'ua_dt_style_responsive',
            [
                'label' => esc_html__( 'Responsive Display', 'ultraaddons-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ua_dt_enable_responsive',
            [
                'label'        => esc_html__( 'Enable Responsive Layout', 'ultraaddons-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ultraaddons-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'ultraaddons-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__( 'Adapts table layout smoothly on smaller screens.', 'ultraaddons-elementor-lite' ),
            ]
        );

        $this->add_control(
            'ua_dt_mobile_layout',
            [
                'label'       => esc_html__( 'Mobile Display Mode', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'card',
                'options'     => [
                    'card'   => esc_html__( 'Card / Stack View (Recommended)', 'ultraaddons-elementor-lite' ),
                    'scroll' => esc_html__( 'Horizontal Scroll View', 'ultraaddons-elementor-lite' ),
                ],
                'condition'   => [
                    'ua_dt_enable_responsive' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_responsive_breakpoint',
            [
                'label'       => esc_html__( 'Breakpoint (px)', 'ultraaddons-elementor-lite' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 768,
                'min'         => 320,
                'max'         => 1200,
                'condition'   => [
                    'ua_dt_enable_responsive' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ua_dt_mobile_label_width',
            [
                'label'      => esc_html__( 'Mobile Label Width', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [ 'px' => [ 'min' => 60, 'max' => 200 ] ],
                'default'    => [ 'size' => 110, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-mobile-th' => 'flex-basis: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_dt_enable_responsive' => 'yes',
                    'ua_dt_mobile_layout'     => 'card',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_mobile_label_color',
            [
                'label'     => esc_html__( 'Mobile Label Color', 'ultraaddons-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .ua-mobile-th' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ua_dt_enable_responsive' => 'yes',
                    'ua_dt_mobile_layout'     => 'card',
                ],
            ]
        );

        $this->add_control(
            'ua_dt_mobile_card_spacing',
            [
                'label'      => esc_html__( 'Card Spacing (Bottom Margin)', 'ultraaddons-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default'    => [ 'size' => 16, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .ua-table-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'ua_dt_enable_responsive' => 'yes',
                    'ua_dt_mobile_layout'     => 'card',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget frontend output.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $header_cols        = $settings['ua_dt_header_cols'] ?? [];
        $content_rows       = $settings['ua_dt_rows'] ?? [];
        $enable_sorting     = ( 'yes' === ( $settings['ua_dt_enable_sorting'] ?? 'no' ) );
        $enable_responsive  = ( 'yes' === ( $settings['ua_dt_enable_responsive'] ?? 'yes' ) );
        $mobile_layout      = $settings['ua_dt_mobile_layout'] ?? 'card';
        $breakpoint         = ! empty( $settings['ua_dt_responsive_breakpoint'] ) ? absint( $settings['ua_dt_responsive_breakpoint'] ) : 768;
        $show_export        = ( 'yes' === ( $settings['ua_dt_show_frontend_export'] ?? 'no' ) );
        $export_btn_text    = ! empty( $settings['ua_dt_frontend_export_text'] ) ? $settings['ua_dt_frontend_export_text'] : esc_html__( 'Export Table (CSV)', 'ultraaddons-elementor-lite' );

        $widget_id = $this->get_id();

        // Extract header names for pairing with cells on mobile
        $col_names = [];
        if ( ! empty( $header_cols ) ) {
            foreach ( $header_cols as $h_item ) {
                $span = ! empty( $h_item['ua_dt_col_span'] ) ? max( 1, absint( $h_item['ua_dt_col_span'] ) ) : 1;
                $name = ! empty( $h_item['ua_dt_col_name'] ) ? esc_html( $h_item['ua_dt_col_name'] ) : '';
                for ( $s = 0; $s < $span; $s++ ) {
                    $col_names[] = $name;
                }
            }
        }

        $wrapper_classes = [
            'ua-data-table-wrapper',
            'ua-dt-' . $widget_id,
        ];

        if ( $enable_responsive ) {
            $wrapper_classes[] = 'ua-responsive-' . esc_attr( $mobile_layout );
        }
        if ( $enable_sorting ) {
            $wrapper_classes[] = 'ua-has-sorting';
        }
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
             id="ua-data-table-<?php echo esc_attr( $widget_id ); ?>"
             data-breakpoint="<?php echo esc_attr( $breakpoint ); ?>"
             data-sorting="<?php echo $enable_sorting ? 'true' : 'false'; ?>">

            <?php if ( $show_export ) : ?>
                <div class="ua-table-toolbar">
                    <button type="button" class="ua-table-export-btn" data-target="#ua-data-table-<?php echo esc_attr( $widget_id ); ?> table">
                        <i class="eicon-download-bold" aria-hidden="true"></i>
                        <span><?php echo esc_html( $export_btn_text ); ?></span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="ua-table-responsive-container">
                <table class="ua-data-table">
                    <?php if ( ! empty( $header_cols ) ) : ?>
                        <thead class="ua-table-head">
                            <tr class="ua-table-head-row">
                                <?php
                                $col_idx = 0;
                                foreach ( $header_cols as $index => $col ) :
                                    $col_span = ! empty( $col['ua_dt_col_span'] ) ? absint( $col['ua_dt_col_span'] ) : 1;
                                    $col_cls  = ! empty( $col['ua_dt_col_class'] ) ? ' ' . esc_attr( $col['ua_dt_col_class'] ) : '';
                                    $col_id   = ! empty( $col['ua_dt_col_id'] ) ? ' id="' . esc_attr( $col['ua_dt_col_id'] ) . '"' : '';
                                    $media    = $col['ua_dt_col_media_type'] ?? 'none';
                                    ?>
                                    <th class="ua-table-th elementor-repeater-item-<?php echo esc_attr( $col['_id'] ?? $index ); ?><?php echo $col_cls; ?>"
                                        <?php echo $col_id; ?>
                                        <?php if ( $col_span > 1 ) : ?>colspan="<?php echo esc_attr( $col_span ); ?>"<?php endif; ?>
                                        data-col-index="<?php echo esc_attr( $col_idx ); ?>">
                                        <div class="ua-th-inner">
                                            <?php if ( 'icon' === $media && ! empty( $col['ua_dt_col_icon']['value'] ) ) : ?>
                                                <span class="ua-header-media ua-header-icon">
                                                    <?php Icons_Manager::render_icon( $col['ua_dt_col_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                </span>
                                            <?php elseif ( 'image' === $media && ! empty( $col['ua_dt_col_image']['url'] ) ) : ?>
                                                <span class="ua-header-media ua-header-img">
                                                    <img src="<?php echo esc_url( $col['ua_dt_col_image']['url'] ); ?>" alt="<?php echo esc_attr( $col['ua_dt_col_name'] ); ?>">
                                                </span>
                                            <?php endif; ?>

                                            <span class="ua-header-text"><?php echo esc_html( $col['ua_dt_col_name'] ); ?></span>

                                            <?php if ( $enable_sorting ) : ?>
                                                <span class="ua-sort-indicator" aria-hidden="true"></span>
                                            <?php endif; ?>
                                        </div>
                                    </th>
                                    <?php
                                    $col_idx++;
                                endforeach;
                                ?>
                            </tr>
                        </thead>
                    <?php endif; ?>

                    <tbody class="ua-table-body">
                        <?php
                        $row_open   = false;
                        $cell_count = 0;

                        if ( ! empty( $content_rows ) ) :
                            foreach ( $content_rows as $c_index => $row_item ) :
                                $row_type = $row_item['ua_dt_row_type'] ?? 'col';

                                if ( 'row' === $row_type ) {
                                    if ( $row_open ) {
                                        echo '</tr>';
                                    }
                                    echo '<tr class="ua-table-row">';
                                    $row_open   = true;
                                    $cell_count = 0;
                                    continue;
                                }

                                if ( ! $row_open ) {
                                    echo '<tr class="ua-table-row">';
                                    $row_open   = true;
                                    $cell_count = 0;
                                }

                                $colspan  = ! empty( $row_item['ua_dt_cell_colspan'] ) ? absint( $row_item['ua_dt_cell_colspan'] ) : 1;
                                $rowspan  = ! empty( $row_item['ua_dt_cell_rowspan'] ) ? absint( $row_item['ua_dt_cell_rowspan'] ) : 1;
                                $cell_cls = ! empty( $row_item['ua_dt_cell_class'] ) ? ' ' . esc_attr( $row_item['ua_dt_cell_class'] ) : '';
                                $cell_id  = ! empty( $row_item['ua_dt_cell_id'] ) ? ' id="' . esc_attr( $row_item['ua_dt_cell_id'] ) . '"' : '';
                                $ctype    = $row_item['ua_dt_cell_content_type'] ?? 'textarea';

                                $mobile_label = isset( $col_names[ $cell_count ] ) ? $col_names[ $cell_count ] : '';
                                ?>
                                <td class="ua-table-td elementor-repeater-item-<?php echo esc_attr( $row_item['_id'] ?? $c_index ); ?><?php echo $cell_cls; ?>"
                                    <?php echo $cell_id; ?>
                                    <?php if ( $colspan > 1 ) : ?>colspan="<?php echo esc_attr( $colspan ); ?>"<?php endif; ?>
                                    <?php if ( $rowspan > 1 ) : ?>rowspan="<?php echo esc_attr( $rowspan ); ?>"<?php endif; ?>
                                    data-col-label="<?php echo esc_attr( $mobile_label ); ?>">

                                    <div class="ua-td-content-wrap">
                                        <?php if ( ! empty( $mobile_label ) ) : ?>
                                            <span class="ua-mobile-th"><?php echo esc_html( $mobile_label ); ?></span>
                                        <?php endif; ?>

                                        <div class="ua-cell-content">
                                            <?php
                                            $has_link = ! empty( $row_item['ua_dt_cell_link']['url'] );
                                            if ( $has_link ) {
                                                $this->add_link_attributes( 'cell_link_' . $c_index, $row_item['ua_dt_cell_link'] );
                                                echo '<a ' . $this->get_render_attribute_string( 'cell_link_' . $c_index ) . '>';
                                            }

                                            switch ( $ctype ) {
                                                case 'editor':
                                                    echo wp_kses_post( $row_item['ua_dt_cell_editor'] ?? '' );
                                                    break;

                                                case 'icon':
                                                    if ( ! empty( $row_item['ua_dt_cell_icon']['value'] ) ) {
                                                        echo '<span class="ua-cell-icon">';
                                                        Icons_Manager::render_icon( $row_item['ua_dt_cell_icon'], [ 'aria-hidden' => 'true' ] );
                                                        echo '</span>';
                                                    }
                                                    break;

                                                case 'template':
                                                    if ( ! empty( $row_item['ua_dt_cell_template'] ) ) {
                                                        echo $this->render_template_content( $row_item['ua_dt_cell_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    }
                                                    break;

                                                case 'textarea':
                                                default:
                                                    echo wp_kses_post( nl2br( $row_item['ua_dt_cell_text'] ?? '' ) );
                                                    break;
                                            }

                                            if ( $has_link ) {
                                                echo '</a>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                $cell_count += $colspan;
                            endforeach;

                            if ( $row_open ) {
                                echo '</tr>';
                            }
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}
