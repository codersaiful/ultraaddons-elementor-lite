<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use UltraAddons\Core\Mega_Menu;

/**
 * Custom Semantic Nav Walker for UltraAddons Navigation Menu & Mega Menu
 * Supports standard multi-level submenus, hover pointers,
 * and UltraAddons Mega Menu integration.
 *
 * @package UltraAddons
 * @since 3.1.0
 */
if ( ! class_exists( 'Ultra_Nav_Walker' ) ) {
    class Ultra_Nav_Walker extends \Walker_Nav_Menu {

        protected $first_item_marked = false;
        protected $is_mobile = false;
        protected $indicator_type = 'classic';
        protected $show_indicator = true;

        public function __construct( $is_mobile = false, $indicator_type = 'classic', $show_indicator = true ) {
            $this->is_mobile      = (bool) $is_mobile;
            $this->indicator_type = ! empty( $indicator_type ) ? $indicator_type : 'classic';
            $this->show_indicator = ( 'no' !== $show_indicator && false !== $show_indicator );
        }

        public function start_lvl( &$output, $depth = 0, $args = null ) {
            $indent = str_repeat( "\t", $depth );
            $output .= "\n$indent<ul class=\"ua-sub-menu ua-sub-level-{$depth}\">\n";
        }

        public function end_lvl( &$output, $depth = 0, $args = null ) {
            $indent = str_repeat( "\t", $depth );
            $output .= "$indent</ul>\n";
        }

        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            // Check for Ultra Mega Menu on top-level items
            $has_mega = ( 0 === $depth ) && class_exists( 'UltraAddons\Core\Mega_Menu' ) && Mega_Menu::has_mega_menu( $item->ID );
            $mega_settings = $has_mega ? Mega_Menu::get_settings( $item->ID ) : null;

            $classes = empty( $item->classes ) ? [] : (array) $item->classes;
            $classes[] = 'ua-nav-item';
            $classes[] = 'menu-item-' . (int) $item->ID;
            if ( 0 === $depth ) {
                $classes[] = 'ua-nav-top-item';

                $is_elementor = false;
                if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance ) ) {
                    $is_elementor = ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() );
                }
                if ( ! $this->first_item_marked && $is_elementor ) {
                    $classes[] = 'ua-first-item';
                    $this->first_item_marked = true;
                }
            }

            if ( $has_mega ) {
                $classes[] = 'ua-has-mega-menu';
                $classes[] = 'menu-item-has-children';
                $classes[] = 'ua-mega-width-' . sanitize_html_class( $mega_settings['width_type'] );
                $classes[] = 'ua-mega-pos-' . sanitize_html_class( $mega_settings['position'] );
            }

            $has_children = in_array( 'menu-item-has-children', $classes, true );

            $li_style = '';
            if ( $has_mega && 'custom' === $mega_settings['width_type'] && ! empty( $mega_settings['custom_width'] ) ) {
                $li_style = ' style="--ua-mega-custom-width: ' . (int) $mega_settings['custom_width'] . 'px;"';
            }

            $class_names = implode( ' ', array_filter( array_map( 'esc_attr', $classes ) ) );
            $output .= $indent . '<li class="' . $class_names . '"' . $li_style . '>';

            $atts = [];
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
            $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
            $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
            $atts['class']  = 'ua-nav-link';

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $title = apply_filters( 'the_title', $item->title, $item->ID );

            // Optional Menu Icon
            $icon_html = '';
            if ( $has_mega && ! empty( $mega_settings['icon'] ) ) {
                $icon_style = 'color: ' . esc_attr( $mega_settings['icon_color'] ) . '; font-size: ' . (int) $mega_settings['icon_size'] . 'px;';
                $icon_html  = '<i class="ua-mega-icon ' . esc_attr( $mega_settings['icon'] ) . '" style="' . $icon_style . '" aria-hidden="true"></i> ';
            }

            // Optional Menu Badge
            $badge_html = '';
            if ( $has_mega && ! empty( $mega_settings['badge_text'] ) ) {
                $bg_color   = ! empty( $mega_settings['badge_bg'] ) ? $mega_settings['badge_bg'] : '#4f46e5';
                $text_color = ! empty( $mega_settings['badge_color'] ) ? $mega_settings['badge_color'] : '#ffffff';
                if ( 'outline' === $mega_settings['badge_style'] ) {
                    $outline_color = ( '#ffffff' === strtolower( $text_color ) || empty( $text_color ) ) ? $bg_color : $text_color;
                    $badge_style   = 'border-color: ' . esc_attr( $outline_color ) . '; color: ' . esc_attr( $outline_color ) . ';';
                } else {
                    $badge_style = 'background-color: ' . esc_attr( $bg_color ) . '; color: ' . esc_attr( $text_color ) . ';';
                }
                $badge_dot   = ( 'pulse-dot' === $mega_settings['badge_animation'] ) ? '<span class="ua-badge-dot" aria-hidden="true"></span>' : '';
                $badge_pos   = ! empty( $mega_settings['badge_position'] ) ? $mega_settings['badge_position'] : 'floating';
                $badge_html  = ' <span class="ua-mega-badge ua-badge-' . esc_attr( $mega_settings['badge_style'] ) . ' ua-badge-pos-' . esc_attr( $badge_pos ) . ' ua-anim-' . esc_attr( $mega_settings['badge_animation'] ) . '" style="' . $badge_style . '">' . esc_html( $mega_settings['badge_text'] ) . $badge_dot . '</span>';
            }

            $item_output  = isset( $args->before ) ? $args->before : '';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $icon_html;
            $item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . '<span class="ua-nav-title">' . $title . '</span>' . ( isset( $args->link_after ) ? $args->link_after : '' );
            $item_output .= $badge_html;

            if ( $has_children && $this->show_indicator ) {
                $item_output .= $this->get_sub_indicator_html( $depth );
            }

            $item_output .= '</a>';
            $item_output .= isset( $args->after ) ? $args->after : '';

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        /**
         * Render crisp semantic SVG Submenu Indicator
         *
         * @param int $depth Menu level depth
         * @return string
         */
        public function get_sub_indicator_html( $depth = 0 ) {
            if ( ! $this->show_indicator ) {
                return '';
            }

            $type = $this->indicator_type ?: 'classic';
            $is_down = ( 0 === $depth );
            $direction_class = $is_down ? 'ua-indicator-down' : 'ua-indicator-right';

            $svg = '';
            switch ( $type ) {
                case 'angle':
                    if ( $is_down ) {
                        $svg = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                    } else {
                        $svg = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
                    }
                    break;

                case 'caret':
                    if ( $is_down ) {
                        $svg = '<svg width="8" height="6" viewBox="0 0 8 6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4 6L0 0H8L4 6Z"/></svg>';
                    } else {
                        $svg = '<svg width="6" height="8" viewBox="0 0 6 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6 4L0 8V0L6 4Z"/></svg>';
                    }
                    break;

                case 'arrow':
                    if ( $is_down ) {
                        $svg = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>';
                    } else {
                        $svg = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
                    }
                    break;

                case 'plus':
                    $svg = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
                    break;

                case 'classic':
                default:
                    if ( $is_down ) {
                        $svg = '<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    } else {
                        $svg = '<svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 9L5 5L1 1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    }
                    break;
            }

            return '<span class="ua-sub-indicator ' . esc_attr( $direction_class ) . ' ua-indicator-' . esc_attr( $type ) . '" aria-hidden="true">' . $svg . '</span>';
        }

        public function end_el( &$output, $item, $depth = 0, $args = null ) {
            if ( 0 === $depth && class_exists( 'UltraAddons\Core\Mega_Menu' ) && Mega_Menu::has_mega_menu( $item->ID ) ) {
                $template_id = Mega_Menu::get_template_id( $item->ID );
                $settings    = Mega_Menu::get_settings( $item->ID );

                if ( ! $this->is_mobile ) {
                    // Desktop Mega Menu Panel
                    $content = Mega_Menu::render_template( $template_id );
                    $output .= '<div class="ua-mega-dropdown ua-sub-menu ua-mega-panel ua-mega-width-' . esc_attr( $settings['width_type'] ) . ' ua-mega-pos-' . esc_attr( $settings['position'] ) . '">';
                    $output .=   '<div class="ua-mega-content-container">';
                    $output .=     $content;
                    $output .=   '</div>';
                    $output .= '</div>';
                } else {
                    // Mobile Mega Menu Handling
                    if ( 'mega' === $settings['mobile_mode'] ) {
                        $content = Mega_Menu::render_template( $template_id );
                        $output .= '<div class="ua-mega-dropdown ua-sub-menu ua-mega-mobile-panel">';
                        $output .=   '<div class="ua-mega-content-container">';
                        $output .=     $content;
                        $output .=   '</div>';
                        $output .= '</div>';
                    } elseif ( 'ajax' === $settings['mobile_mode'] ) {
                        $output .= '<div class="ua-mega-dropdown ua-sub-menu ua-mega-ajax" data-template-id="' . (int) $template_id . '">';
                        $output .=   '<div class="ua-mega-ajax-placeholder"><span class="ua-spinner"></span> ' . esc_html__( 'Loading...', 'ultraaddons-elementor-lite' ) . '</div>';
                        $output .= '</div>';
                    }
                    // If 'wp_sub', standard WordPress sub-items render normally
                }
            }

            $output .= "</li>\n";
        }
    }

    if ( ! class_exists( 'UltraAddons\Widget\Ultra_Nav_Walker' ) ) {
        class_alias( 'Ultra_Nav_Walker', 'UltraAddons\Widget\Ultra_Nav_Walker' );
    }
}
