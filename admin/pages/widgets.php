<?php

use UltraAddons\Core\Widgets_Manager;

defined( 'ABSPATH' ) || die();

$ultraaddons_items = Widgets_Manager::widgets();
if ( is_array( $ultraaddons_items ) ) {
    uasort( $ultraaddons_items, function ( $a, $b ) {
        return strcasecmp( $a['name'] ?? '', $b['name'] ?? '' );
    } );
}
if ( isset( $_POST['ultraaddons_nonce'] ) ) {
    if ( ! current_user_can( ULTRA_ADDONS_CAPABILITY ) ) {
        wp_die( esc_html__( 'You are not allowed to manage UltraAddons widgets.', 'ultraaddons-elementor-lite' ) );
    }

    check_admin_referer( 'ultraaddons_save_widgets', 'ultraaddons_nonce' );
    $submitted_items = isset( $_POST['item'] ) && is_array( $_POST['item'] )
        ? array_map( 'sanitize_text_field', wp_unslash( $_POST['item'] ) )
        : [];
    $disabled_items = array_values( array_intersect( $submitted_items, array_keys( $ultraaddons_items ) ) );
    update_option( Widgets_Manager::$disabled_items_key, $disabled_items );
}
// $ultraaddons_items['More'] = [
//             'name'      => __( 'More Widget Comming Soon ....', 'ultraaddons-elementor-lite' ),
//             'is_pro'   => true,
//             'icon'      => 'uicon-ultraaddons',//eicon-global-colors
//             'cat'       => [
//                 __( 'Basic', 'ultraaddons-elementor-lite' ),
//             ],
//     ];
$ultraaddons_disable_items = Widgets_Manager::disableWidgetKeys();

$ultraaddons_temp_widgets = $ultraaddons_items;
$ultraaddons_wid_cats = [];
foreach( $ultraaddons_temp_widgets as $ultraaddons_temp_wid_key => $ultraaddons_temp_wdget ){
    $cat = $ultraaddons_temp_wdget['cat'][0] ?? 'no-cat';
    $cat_name = str_replace( '_', ' ', $cat );
    $ultraaddons_wid_cats[$cat] = ucwords( $cat_name );
}
$total_count = count( $ultraaddons_items );
$free_count  = 0;
$pro_count   = 0;
foreach ( $ultraaddons_items as $item ) {
    if ( ! empty( $item['is_pro'] ) ) {
        $pro_count++;
    } else {
        $free_count++;
    }
}
?>

<div class="ultraaddons-section ua-option-wrapper ua-widgets-page">
    <div class="ua-section-inside">
        
        <!-- Page Intro Header -->
        <div class="ua-page-intro-header">
            <h2 class="ua-page-intro-title"><?php echo esc_html__( 'Ultra Widgets', 'ultraaddons-elementor-lite' ); ?></h2>
            <p class="ua-page-intro-desc"><?php echo esc_html__( 'Enable only the widgets you need to keep your Elementor editor fast and clean.', 'ultraaddons-elementor-lite' ); ?></p>
        </div>

        <div class="ua-elements-header-card">
            <div class="ua-filter-bar-row">
                <div class="ua-filter-search-box">
                    <svg class="ua-search-svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" id="ua-widget-search" class="ua-search-field" placeholder="<?php echo esc_attr__( 'Search widgets...', 'ultraaddons-elementor-lite' ); ?>" autocomplete="off" />
                    <button type="button" class="ua-search-clear" id="ua-search-clear" title="<?php echo esc_attr__( 'Clear search', 'ultraaddons-elementor-lite' ); ?>">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="ua-filter-category-box">
                    <select id="ua-widget-category-select" class="ua-category-select">
                        <option value="category-all"><?php echo esc_html__( 'All Categories', 'ultraaddons-elementor-lite' ); ?></option>
                        <?php foreach( $ultraaddons_wid_cats as $cat_slug => $cat_title ) : ?>
                            <option value="<?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $cat_title ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ua-filter-tabs-group">
                    <button type="button" class="ua-tab-pill ua-tab-all active" data-target="free-pro-all">
                        <span class="ua-tab-label"><?php echo esc_html__( 'All', 'ultraaddons-elementor-lite' ); ?></span>
                    </button>
                    <button type="button" class="ua-tab-pill ua-tab-free" data-target="free">
                        <span class="ua-tab-label"><?php echo esc_html__( 'Free', 'ultraaddons-elementor-lite' ); ?></span>
                    </button>
                    <button type="button" class="ua-tab-pill ua-tab-pro" data-target="pro">
                        <span class="ua-tab-label"><?php echo esc_html__( 'Pro', 'ultraaddons-elementor-lite' ); ?></span>
                    </button>
                </div>

                <div class="ua-header-actions">
                    <div class="ua-master-toggle-wrap">
                        <span class="ua-toggle-text" id="ua-toggle-all-label"><?php echo esc_html__( 'Enable All Elements', 'ultraaddons-elementor-lite' ); ?></span>
                        <label class="ua-modern-switch" for="ua-enable-all-elements">
                            <input type="checkbox" id="ua-enable-all-elements" />
                            <span class="ua-modern-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">
                <div class="ua-no-widgets-found" style="display: none;">
                    <p><?php echo esc_html__( 'No elements found matching your criteria.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>

                <form class="ua-option-list-form" id="ua-widget-form" action="" method="post">
                    <?php wp_nonce_field( 'ultraaddons_save_widgets', 'ultraaddons_nonce' ); ?>
                    <div class="ua-option-item-wrappper">
                        <?php 
                        foreach( $ultraaddons_items as $ultraaddons_class_name => $ultraaddons_item ){

                            $ultraaddons_name = isset( $ultraaddons_item['name'] ) ? $ultraaddons_item['name'] : false;
                            $ultraaddons_icon = isset( $ultraaddons_item['icon'] ) ? $ultraaddons_item['icon'] : false;
                            $ultraaddons_badge = isset( $ultraaddons_item['badge'] ) ? $ultraaddons_item['badge'] : false;
                            $cat = isset( $ultraaddons_item['cat'] ) && is_array( $ultraaddons_item['cat'] ) ? $ultraaddons_item['cat'] : [];
                            $ultraaddons_free_pro = isset( $ultraaddons_item['is_pro'] ) && $ultraaddons_item['is_pro'] ? 'pro' : 'free';
                            
                            /**
                             * On or OFF feature will stay or not
                             * it will depend on this class
                             * and
                             * we customized this class based on
                             * pro available or not.
                             * 
                             * zodi pr thake tahole sob e on or off kora zabe.
                             * r jodi na thake, tahole sudhu free guloi onOff kora zabe.
                             * 
                             * eta ber korar jonno ami
                             * prothome check korechi, see free naki pro
                             * jodi free hoy to sob somoy change able.
                             * r jodi pr hoy, tobe pr thaklei changeable hobe.
                             * 
                             * @since 1.0.7.17
                             */
                            $ultraaddons_item_oo_option = isset( $ultraaddons_item['is_pro'] ) && $ultraaddons_item['is_pro'] && ! ultraaddons_is_pro()  ? 'item_on_off_disable' : 'item_on_off_enable';
                            
                            $ultraaddons_checkbox = in_array( $ultraaddons_class_name, $ultraaddons_disable_items ) ? 'checked' : '';
                            $ultraaddons_enbl_disbl_class = in_array( $ultraaddons_class_name, $ultraaddons_disable_items ) ? 'disabled' : 'enabled';
                            $ultraaddons_checkbox_id = 'checkbox_' . $ultraaddons_class_name;
                            $ultraaddons_html_class = [];
                            //$ultraaddons_html_class[] = $ultraaddons_name;
                            $ultraaddons_html_class[] = $ultraaddons_enbl_disbl_class;
                            $ultraaddons_html_class[] = $ultraaddons_item_oo_option;
                            //$ultraaddons_html_class[] = $ultraaddons_icon;
                            $ultraaddons_html_class[] = $ultraaddons_free_pro;
                            $ultraaddons_html_class[] = $ultraaddons_class_name;
                            if ( ! empty( $ultraaddons_badge ) ) {
                                $ultraaddons_html_class[] = 'ua-has-badge';
                            }

                            $widget_slug = strtolower( str_replace( '_', '-', $ultraaddons_class_name ) );
                            $demo_url = ! empty( $ultraaddons_item['demo_url'] ) ? $ultraaddons_item['demo_url'] : 'https://ultraaddons.com/widget/' . $widget_slug . '/';
                        ?>
                        <label data-name="<?php echo esc_attr( $ultraaddons_name ); ?>" 
                             for="<?php echo esc_attr( $ultraaddons_checkbox_id ); ?>"
                             data-object_name="<?php echo esc_attr( $ultraaddons_class_name ); ?>"
                             data-category="<?php echo esc_attr( implode( ',', $cat ) ); ?>"
                             data-type="<?php echo esc_attr( $ultraaddons_free_pro ); ?>"
                             class="ua-option-item <?php echo esc_attr( implode( " ", $ultraaddons_html_class ) ); ?> <?php echo esc_attr( implode( ',', $cat ) ); ?>">
                            <div class="ua-option-item-inside">
                                <div class="ua-item-icon-box">
                                    <i class="ua-option-icon <?php echo esc_attr( $ultraaddons_icon ); ?>"></i>
                                </div>
                                <div class="ua-item-content">
                                    <h2 class="ua-item-name">
                                        <span class="ua-name-text"><?php echo esc_html( $ultraaddons_name ); ?></span>
                                        <?php if ( $ultraaddons_free_pro === 'pro' ) : ?>
                                            <span class="ua-option-version-type ua-option-version-type-pro"><?php echo esc_html__( 'Pro', 'ultraaddons-elementor-lite' ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $ultraaddons_badge ) ) : ?>
                                            <span class="ua-option-badge ua-option-badge-<?php echo esc_attr( strtolower( $ultraaddons_badge ) ); ?>"><?php echo esc_html( $ultraaddons_badge ); ?></span>
                                        <?php endif; ?>
                                    </h2>
                                    <div class="ua-item-actions">
                                        <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" class="ua-item-action-btn ua-btn-demo" title="<?php esc_attr_e( 'Live Preview', 'ultraaddons-elementor-lite' ); ?>" onclick="event.stopPropagation();">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="ua-option-checkbox">
                                    <input class="ua-checkbox-hidden" id="<?php echo esc_attr( $ultraaddons_checkbox_id ); ?>" type="checkbox" name="item[]" value="<?php echo esc_attr( $ultraaddons_class_name ); ?>" <?php echo esc_attr( $ultraaddons_checkbox ); ?>>
                                    <div class="ua-designed-checkbox"></div>
                                </div>
                            </div>
                        </label>
                        <?php } ?>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<?php
/**
* Modal for pro widget
* @aurhor B M Rafiul Alam
* Email: bmrafiul.alam@gmail.com
* @since 1.1.0.10
*/
?>
<div class="remodal" data-remodal-id="modal">
  <button data-remodal-action="close" class="remodal-close"></button>
  <img class="popup-image" src=" <?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/popup-pro.png' ); ?>">
  <h1>Go Pro</h1>
  <p>
    Unlock 30+ amazing widgets to build awesome websites.
  </p>
  <br>
  <a href="https://ultraaddons.com/pricing/" target="_blank" class="remodal-confirm">Upgrade Now</a>
</div>
