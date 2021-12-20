<?php
/**
 * Template library templates
 * 
 * 'back_button_text' => esc_html__( 'Back to Library', 'ultraaddons' ),
            'lern_more_message' => esc_html__( 'Learn more about UltraAddons Template Library.', 'ultraaddons' ),
            'page_template' => 'https://ultraaddons.com/page-templates/',
 */
use UltraAddons\Library\Demo\Theme_Demo;

defined( 'ABSPATH' ) || exit;

$theme_info = Theme_Demo::get_demo_info();

$back_button_text = $theme_info['back_button_text'] ?? __( 'Back to Library', 'ultraaddons' );
$library_icon = $theme_info['library_icon'] ?? 'uicon-ultraaddons';
$page_templates = $theme_info['page_templates'] ?? 'https://ultraaddons.com/page-templates/';
$lern_more_message = $theme_info['lern_more_message'] ?? __( 'Learn more about UltraAddons Template Library.', 'ultraaddons' );

?>
<script type="text/template" id="tmpl-EldmTempDemo__header-logo">
    <span class="EldmTempDemo__logo-wrap">
		<i class="<?php echo esc_attr( $library_icon ); ?>"></i>
	</span>
    <span class="EldmTempDemo__logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo esc_html( $back_button_text ); ?></span>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
		<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__header-menu-responsive">
	<div class="elementor-component-tab EldmTempDemo__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'ultraaddons' ); ?></span>
	</div>
	<div class="elementor-component-tab EldmTempDemo__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'ultraaddons' ); ?></span>
	</div>
	<div class="elementor-component-tab EldmTempDemo__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'ultraaddons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__header-actions">
	<div id="EldmTempDemo__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'ultraaddons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ ultraaddons.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__insert-button">
	<a class="elementor-template-library-template-action elementor-button EldmTempDemo__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'ultraaddons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__pro-button">
	<a class="elementor-template-library-template-action elementor-button EldmTempDemo__pro-button" href="https://ultraaddons.com/pricing/" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'ultraaddons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'ultraaddons' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__templates">
	<div id="EldmTempDemo__toolbar">
		<div id="EldmTempDemo__toolbar-filter" class="EldmTempDemo__toolbar-filter">
			<# if (ultraaddons.library.getTypeTags()) { var selectedTag = ultraaddons.library.getFilter( 'tags' ); #>
				<# if ( selectedTag ) { #>
				<span class="EldmTempDemo__filter-btn">{{{ ultraaddons.library.getTags()[selectedTag] }}} <i class="eicon-caret-right"></i></span>
				<# } else { #>
				<span class="EldmTempDemo__filter-btn"><?php esc_html_e( 'Filter', 'ultraaddons' ); ?> <i class="eicon-caret-right"></i></span>
				<# } #>
				<ul id="EldmTempDemo__filter-tags" class="EldmTempDemo__filter-tags">
					<li data-tag="">All</li>
					<# _.each(ultraaddons.library.getTypeTags(), function(slug) {
						var selected = selectedTag === slug ? 'active' : '';
						#>
						<li data-tag="{{ slug }}" class="{{ selected }}">{{{ ultraaddons.library.getTags()[slug] }}}</li>
					<# } ); #>
				</ul>
			<# } #>
		</div>
		<div id="EldmTempDemo__toolbar-counter"></div>
		<div id="EldmTempDemo__toolbar-search">
			<label for="EldmTempDemo__search" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'ultraaddons' ); ?></label>
			<input id="EldmTempDemo__search" placeholder="<?php esc_attr_e( 'Search', 'ultraaddons' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>

	<div class="EldmTempDemo__templates-window">
		<div id="EldmTempDemo__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__template">
	<div class="EldmTempDemo__template-body" id="uaTemplate-{{ template_id }}">
		<div class="EldmTempDemo__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
		<img class="EldmTempDemo__template-thumbnail" src="{{ thumbnail }}">
                    
		<# if ( obj.isPro ) { #>
                <span class="EldmTempDemo__template-badge hasPro_<?php echo esc_attr( ultraaddons_plugin_version() ); ?>"><?php esc_html_e( 'Pro', 'ultraaddons' ); ?></span>
		<# } #>
		<# if ( extra.message ) { #>
                <span title="{{ extra.message }}" class="EldmTempDemo__template-alert"><i class="eicon-alert"></i></span>
		<# } #>
	</div>
	<div class="EldmTempDemo__template-stats" <?php echo esc_html__( 'Status automatically updates on a daily basis.', 'ultraaddons' ); ?>>
		<span class="EldmTempDemo-stats views" title="Views">
                    <i class="eicon-preview-thin" aria-hidden="true"></i>
                    <i class="ultra-temp-stats-number">{{extra.views}}</i>    
                </span>
		<span class="EldmTempDemo-stats download" title="Downloads">
                    <i class="eicon-library-download" aria-hidden="true"></i>
                    <i class="ultra-temp-stats-number">{{extra.download}}</i>
                </span>
	</div>
	<div class="EldmTempDemo__template-footer">
		{{{ ultraaddons.library.getModal().getTemplateActionButton( obj ) }}}
                <span class="EldmTempDemo-footer-title">{{{ title }}}</span>
		<a href="#" class="elementor-button EldmTempDemo__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'ultraaddons' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-EldmTempDemo__empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg'; ?>" class="elementor-template-library-no-results" />
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php echo esc_html( $lern_more_message ); ?>
		<a class="elementor-template-library-blank-footer-link" href="<?php echo esc_url( $page_templates ); ?>" target="_blank"><?php echo __( 'Click here', 'ultraaddons' ); ?></a>
	</div>
</script>
