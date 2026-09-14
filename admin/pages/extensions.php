<?php

use UltraAddons\Core\Extensions_Manager;

defined( 'ABSPATH' ) || die();

$ultraaddons_items = Extensions_Manager::get_list();
if ( is_array( $ultraaddons_items ) ) {
    uasort( $ultraaddons_items, function ( $a, $b ) {
        return strcasecmp( $a['name'] ?? '', $b['name'] ?? '' );
    } );
}
if ( isset( $_POST['ultraaddons_nonce'] ) ) {
    if ( ! current_user_can( ULTRA_ADDONS_CAPABILITY ) ) {
        wp_die( esc_html__( 'You are not allowed to manage UltraAddons extensions.', 'ultraaddons-elementor-lite' ) );
    }

    check_admin_referer( 'ultraaddons_save_extensions', 'ultraaddons_nonce' );
    $submitted_items = isset( $_POST['item'] ) && is_array( $_POST['item'] )
        ? array_map( 'sanitize_text_field', wp_unslash( $_POST['item'] ) )
        : [];
    $disabled_items = array_values( array_intersect( $submitted_items, array_keys( $ultraaddons_items ) ) );
    update_option( Extensions_Manager::$disabled_items_key, $disabled_items );
}
$ultraaddons_disable_item = Extensions_Manager::disableExtensionKeys();

$total_count = count( $ultraaddons_items );
$free_count  = 0;
$pro_count   = 0;
$ultraaddons_ext_cats = [];
foreach ( $ultraaddons_items as $item ) {
    if ( ! empty( $item['is_pro'] ) ) {
        $pro_count++;
    } else {
        $free_count++;
    }
    if ( ! empty( $item['cat'] ) && is_array( $item['cat'] ) ) {
        $c = $item['cat'][0] ?? '';
        if ( ! empty( $c ) ) {
            $ultraaddons_ext_cats[$c] = ucwords( str_replace( '_', ' ', $c ) );
        }
    }
}
?>

<div class="ultraaddons-section ua-option-wrapper ua-extensions-page">
    <div class="ua-section-inside">
        <div class="ua-elements-header-card">
            <!-- Top Row: Branding, Stats & Master Actions -->
            <div class="ua-header-top-row">
                <div class="ua-title-area">
                    <div class="ua-title-group">
                        <h1 class="ua-main-title"><?php echo esc_html__( 'Extensions', 'ultraaddons-elementor-lite' ); ?></h1>
                        <span class="ua-title-count-pill"><?php echo esc_html( $total_count ); ?> <?php echo esc_html__( 'Extensions', 'ultraaddons-elementor-lite' ); ?></span>
                    </div>
                    <p class="ua-sub-title"><?php echo esc_html__( 'Enable or disable features and enhancements to customize your workflow.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>

                <div class="ua-header-actions">
                    <div class="ua-master-toggle-wrap">
                        <span class="ua-toggle-text" id="ua-toggle-all-label"><?php echo esc_html__( 'Enable All Extensions', 'ultraaddons-elementor-lite' ); ?></span>
                        <label class="ua-modern-switch" for="ua-enable-all-elements">
                            <input type="checkbox" id="ua-enable-all-elements" />
                            <span class="ua-modern-slider"></span>
                        </label>
                    </div>

                    <button type="submit" form="ua-extension-form" class="ua-btn-save-settings">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span><?php echo esc_html__( 'Save Changes', 'ultraaddons-elementor-lite' ); ?></span>
                    </button>
                </div>
            </div>

            <!-- Bottom Row: Smart Filters (Search, Category if any, All/Free/Pro Tabs) -->
            <div class="ua-filter-bar-row">
                <div class="ua-filter-search-box">
                    <svg class="ua-search-svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" id="ua-widget-search" class="ua-search-field" placeholder="<?php echo esc_attr__( 'Search extension by name...', 'ultraaddons-elementor-lite' ); ?>" autocomplete="off" />
                </div>

                <?php if ( ! empty( $ultraaddons_ext_cats ) ) : ?>
                <div class="ua-filter-category-box">
                    <select id="ua-widget-category-select" class="ua-category-select">
                        <option value="category-all"><?php echo esc_html__( 'All Categories', 'ultraaddons-elementor-lite' ); ?> (<?php echo esc_html( $total_count ); ?>)</option>
                        <?php foreach( $ultraaddons_ext_cats as $cat_slug => $cat_title ) : ?>
                            <option value="<?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $cat_title ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="ua-filter-tabs-group">
                    <button type="button" class="ua-tab-pill ua-tab-all active" data-target="free-pro-all">
                        <span class="ua-tab-label"><?php echo esc_html__( 'All', 'ultraaddons-elementor-lite' ); ?></span>
                        <span class="ua-pill-count"><?php echo esc_html( $total_count ); ?></span>
                    </button>
                    <button type="button" class="ua-tab-pill ua-tab-free" data-target="free">
                        <span class="ua-tab-label"><?php echo esc_html__( 'Free', 'ultraaddons-elementor-lite' ); ?></span>
                        <span class="ua-pill-count"><?php echo esc_html( $free_count ); ?></span>
                    </button>
                    <button type="button" class="ua-tab-pill ua-tab-pro" data-target="pro">
                        <span class="ua-tab-label"><?php echo esc_html__( 'Pro', 'ultraaddons-elementor-lite' ); ?></span>
                        <span class="ua-pill-count ua-pro-count"><?php echo esc_html( $pro_count ); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">
                <div class="ua-no-widgets-found" style="display: none;">
                    <p><?php echo esc_html__( 'No extensions found matching your criteria.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>

                <form class="ua-option-list-form" id="ua-extension-form" action="" method="post">
                    <?php wp_nonce_field( 'ultraaddons_save_extensions', 'ultraaddons_nonce' ); ?>
                    <div class="ua-option-item-wrappper">
                        <?php 
                        foreach( $ultraaddons_items as $ultraaddons_class_name => $ultraaddons_item ){

                            $ultraaddons_name = isset( $ultraaddons_item['name'] ) ? $ultraaddons_item['name'] : false;
                            $ultraaddons_icon = isset( $ultraaddons_item['icon'] ) ? $ultraaddons_item['icon'] : false;
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
                            
                            
                            
                            $ultraaddons_checkbox = in_array( $ultraaddons_class_name, $ultraaddons_disable_item ) ? 'checked' : '';
                            $ultraaddons_enbl_disbl_class = in_array( $ultraaddons_class_name, $ultraaddons_disable_item ) ? 'disabled' : 'enabled';
                            
                            $ultraaddons_checkbox_id = 'checkbox_' . $ultraaddons_class_name;
                            $ultraaddons_html_class = [];
//                            $ultraaddons_html_class[] = $ultraaddons_name;
                            $ultraaddons_html_class[] = $ultraaddons_enbl_disbl_class;
                            $ultraaddons_html_class[] = $ultraaddons_item_oo_option;
                            //$ultraaddons_html_class[] = $ultraaddons_icon;
                            $ultraaddons_html_class[] = $ultraaddons_free_pro;
                            $ultraaddons_html_class[] = $ultraaddons_class_name;

                            $ext_slug = strtolower( str_replace( '_', '-', $ultraaddons_class_name ) );
                            $demo_url = ! empty( $ultraaddons_item['demo_url'] ) ? $ultraaddons_item['demo_url'] : 'https://ultraaddons.com/extensions/';
                            $doc_url  = ! empty( $ultraaddons_item['doc_url'] ) ? $ultraaddons_item['doc_url'] : 'https://ultraaddons.com/docs/' . $ext_slug . '/';
                        ?>
                        <label data-name="<?php echo esc_attr( $ultraaddons_name ); ?>" 
                             for="<?php echo esc_attr( $ultraaddons_checkbox_id ); ?>"
                             data-object_name="<?php echo esc_attr( $ultraaddons_class_name ); ?>"
                             data-category="<?php echo esc_attr( implode( ',', $cat ) ); ?>"
                             data-type="<?php echo esc_attr( $ultraaddons_free_pro ); ?>"
                             class="ua-option-item <?php echo esc_attr( implode( " ", $ultraaddons_html_class ) ); ?>">
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
                                    </h2>
                                </div>
                                <div class="ua-item-actions">
                                    <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" class="ua-item-action-btn ua-btn-demo" title="<?php esc_attr_e( 'Live Preview', 'ultraaddons-elementor-lite' ); ?>" onclick="event.stopPropagation();">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <a href="<?php echo esc_url( $doc_url ); ?>" target="_blank" class="ua-item-action-btn ua-btn-doc" title="<?php esc_attr_e( 'Documentation', 'ultraaddons-elementor-lite' ); ?>" onclick="event.stopPropagation();">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    </a>
                                </div>
                                <div class="ua-option-checkbox">
                                    <input class="ua-checkbox-hidden" id="<?php echo esc_attr( $ultraaddons_checkbox_id ); ?>" type="checkbox" name="item[]" value="<?php echo esc_attr( $ultraaddons_class_name ); ?>" <?php echo esc_attr( $ultraaddons_checkbox ); ?>>
                                    <div class="ua-designed-checkbox"></div>
                                </div>
                            </div>
                        </label>
                        <?php } ?>
                    </div>
                    <div class="ua-widget-footer">
                        <button class="primary button button-primary ua-primary ua-no-update" type="submit" name="submit" value="submit"><?php echo esc_html__( 'Save Change', 'ultraaddons-elementor-lite' ); ?></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<?php
/**
* Modal for pro Extension
* @aurhor B M Rafiul Alam
* Email: bmrafiul.alam@gmail.com
* @since 1.1.0.10
*/
?>
<div class="remodal" data-remodal-id="modal">
  <button data-remodal-action="close" class="remodal-close"></button>
  <img class="popup-image" src=" <?php echo esc_attr( ULTRA_ADDONS_ASSETS ) . 'images/popup-pro.png'; ?>">
  <h1>Go Pro</h1>
  <p>
    Unlock 4+ amazing extension to build awesome websites.
  </p>
  <br>
  <a href="https://ultraaddons.com/pricing/" target="_blank" class="remodal-confirm">Upgrade Now</a>
</div>
