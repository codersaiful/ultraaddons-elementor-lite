<?php
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Utils;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Categories extends Base{
    
        
        /**
         * Find by search
         *
         * Retrieve widget title.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string keywords
         */
        public function get_keywords() {
            return [ 'ultraaddons', 'product', 'category', 'categories', 'slider' ];
        }
        
        /**
         * Retrieve the list of scripts the counter widget depended on.
         *
         * Used to set scripts dependencies required to run the widget.
         *
         * @since 1.0.0.13
         * @access public
         *
         * @return array Widget scripts dependencies.
         * @by Saiful
         */
        public function get_script_depends() {
                return [ 'jquery' ];
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
//            $this->content_general_controls();
//
//            //For Design Section Style Tab
//            $this->style_general_controls();
//            $this->slider_controls();
            
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'elementor-products-columns%s-',
				'default' => '4',
				'min' => 1,
				'max' => 5,
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Categories Count', 'ultraaddons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '4',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Query', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Show All', 'ultraaddons' ),
					'by_id' => __( 'Manual Selection', 'ultraaddons' ),
					'by_parent' => __( 'By Parent', 'ultraaddons' ),
					'current_subcategories' => __( 'Current Subcategories', 'ultraaddons' ),
				],
				'label_block' => true,
			]
		);

		$categories = get_terms( 'product_cat' );

		$options = [];
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $options,
				'default' => [],
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'source' => 'by_id',
				],
			]
		);

		$parent_options = [ '0' => __( 'Only Top Level', 'ultraaddons' ) ] + $options;
		$this->add_control(
			'parent',
			[
				'label' => __( 'Parent', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $parent_options,
				'condition' => [
					'source' => 'by_parent',
				],
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label' => __( 'Hide Empty', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Hide',
				'label_off' => 'Show',
			]
		);
                
                $this->add_control(
			'hide_product_count',
			[
				'label' => __( 'Hide Product Count', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Hide',
				'label_off' => 'Show',
                                'selectors' => [
                                        '{{WRAPPER}} mark.count' => 'display: none;',
                                ],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => __( 'Name', 'ultraaddons' ),
					'slug' => __( 'Slug', 'ultraaddons' ),
					'description' => __( 'Description', 'ultraaddons' ),
					'count' => __( 'Count', 'ultraaddons' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'ultraaddons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'ultraaddons' ),
					'desc' => __( 'DESC', 'ultraaddons' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products_style',
			[
				'label' => __( 'Products', 'ultraaddons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_style_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'ultraaddons' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'products_class',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'wc-products',
				'prefix_class' => 'elementor-products-grid elementor-',
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'     => __( 'Columns Gap', 'ultraaddons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => __( 'Rows Gap', 'ultraaddons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'ultraaddons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'ultraaddons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ultraaddons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'ultraaddons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-product-loop-item--align-',
				'selectors' => [
					'{{WRAPPER}} .product' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'label'     => __( 'Image', 'ultraaddons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} ul.products li.product',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'ultraaddons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label'      => __( 'Padding', 'ultraaddons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
                                'default'    => [
                                        'size' => 20,
                                ], 
				'selectors'  => [
					'{{WRAPPER}} ul.products li.product' => 'padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label'     => __( 'Title', 'ultraaddons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce .woocommerce-loop-category__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-loop-category__title',
			]
		);

		$this->add_control(
			'heading_count_style',
			[
				'label'     => __( 'Count', 'ultraaddons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'count_color',
			[
				'label'     => __( 'Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-loop-category__title .count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'count_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .woocommerce-loop-category__title .count',
			]
		);

		$this->end_controls_section();

        }
        
        private function get_shortcode() {
		$settings = $this->get_settings();

		$attributes = [
			'number' => $settings['number'],
			'columns' => $settings['columns'],
			'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
		];

		if ( 'by_id' === $settings['source'] ) {
			$attributes['ids'] = implode( ',', $settings['categories'] );
		} elseif ( 'by_parent' === $settings['source'] ) {
			$attributes['parent'] = $settings['parent'];
		} elseif ( 'current_subcategories' === $settings['source'] ) {
			$attributes['parent'] = get_queried_object_id();
		}

		$this->add_render_attribute( 'shortcode', $attributes );

		$shortcode = sprintf( '[product_categories %s]', $this->get_render_attribute_string( 'shortcode' ) );

		return $shortcode;
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

//                $settings = $this->get_settings_for_display();
        echo do_shortcode( $this->get_shortcode() );
                
        
        }
        
        public function render_plain_content() {
		echo $this->get_shortcode();
	}
    
        
        
    
}