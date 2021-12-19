<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;

?>
<script type="text/template" id="tmpl-UltraThemeLibrary__header-logo">
    <span class="UltraThemeLibrary__logo-wrap">
		<i class="uicon-ultraaddons"></i>
	</span>
    <span class="UltraThemeLibrary__logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'ultraaddons' ); ?></span>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
		<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__header-menu-responsive">
	<div class="elementor-component-tab UltraThemeLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'ultraaddons' ); ?></span>
	</div>
	<div class="elementor-component-tab UltraThemeLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'ultraaddons' ); ?></span>
	</div>
	<div class="elementor-component-tab UltraThemeLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'ultraaddons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__header-actions">
	<div id="UltraThemeLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'ultraaddons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'ultraaddons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ ultraaddons.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button UltraThemeLibrary__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'ultraaddons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__pro-button">
	<a class="elementor-template-library-template-action elementor-button UltraThemeLibrary__pro-button" href="https://ultraaddons.com/pricing/" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'ultraaddons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__loading">
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

<script type="text/template" id="tmpl-UltraThemeLibrary__templates">
	<div id="UltraThemeLibrary__toolbar">
		<div id="UltraThemeLibrary__toolbar-filter" class="UltraThemeLibrary__toolbar-filter">
			<# if (ultraaddons.library.getTypeTags()) { var selectedTag = ultraaddons.library.getFilter( 'tags' ); #>
				<# if ( selectedTag ) { #>
				<span class="UltraThemeLibrary__filter-btn">{{{ ultraaddons.library.getTags()[selectedTag] }}} <i class="eicon-caret-right"></i></span>
				<# } else { #>
				<span class="UltraThemeLibrary__filter-btn"><?php esc_html_e( 'Filter', 'ultraaddons' ); ?> <i class="eicon-caret-right"></i></span>
				<# } #>
				<ul id="UltraThemeLibrary__filter-tags" class="UltraThemeLibrary__filter-tags">
					<li data-tag="">All</li>
					<# _.each(ultraaddons.library.getTypeTags(), function(slug) {
						var selected = selectedTag === slug ? 'active' : '';
						#>
						<li data-tag="{{ slug }}" class="{{ selected }}">{{{ ultraaddons.library.getTags()[slug] }}}</li>
					<# } ); #>
				</ul>
			<# } #>
		</div>
		<div id="UltraThemeLibrary__toolbar-counter"></div>
		<div id="UltraThemeLibrary__toolbar-search">
			<label for="UltraThemeLibrary__search" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'ultraaddons' ); ?></label>
			<input id="UltraThemeLibrary__search" placeholder="<?php esc_attr_e( 'Search', 'ultraaddons' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>

	<div class="UltraThemeLibrary__templates-window">
		<div id="UltraThemeLibrary__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__template">
	<div class="UltraThemeLibrary__template-body" id="uaTemplate-{{ template_id }}">
		<div class="UltraThemeLibrary__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
		<img class="UltraThemeLibrary__template-thumbnail" src="{{ thumbnail }}">
                    
		<# if ( obj.isPro ) { #>
                <span class="UltraThemeLibrary__template-badge hasPro_<?php echo esc_attr( ultraaddons_plugin_version() ); ?>"><?php esc_html_e( 'Pro', 'ultraaddons' ); ?></span>
		<# } #>
		<# if ( extra.message ) { #>
                <span title="{{ extra.message }}" class="UltraThemeLibrary__template-alert"><i class="eicon-alert"></i></span>
		<# } #>
	</div>
	<div class="UltraThemeLibrary__template-stats" <?php echo esc_html__( 'Status automatically updates on a daily basis.', 'ultraaddons' ); ?>>
		<span class="UltraThemeLibrary-stats views" title="Views">
                    <i class="eicon-preview-thin" aria-hidden="true"></i>
                    <i class="ultra-temp-stats-number">{{extra.views}}</i>    
                </span>
		<span class="UltraThemeLibrary-stats download" title="Downloads">
                    <i class="eicon-library-download" aria-hidden="true"></i>
                    <i class="ultra-temp-stats-number">{{extra.download}}</i>
                </span>
	</div>
	<div class="UltraThemeLibrary__template-footer">
		{{{ ultraaddons.library.getModal().getTemplateActionButton( obj ) }}}
                <span class="UltraThemeLibrary-footer-title">{{{ title }}}</span>
		<a href="#" class="elementor-button UltraThemeLibrary__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'ultraaddons' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-UltraThemeLibrary__empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg'; ?>" class="elementor-template-library-no-results" />
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php esc_html_e( 'Learn more about UltraAddons Template Library.', 'ultraaddons' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://ultraaddons.com/template-library" target="_blank"><?php echo __( 'Click here', 'ultraaddons' ); ?></a>
	</div>
</script>
