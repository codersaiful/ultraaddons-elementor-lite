<?php
defined( 'ABSPATH' ) || die();

$is_pro = function_exists( 'ultraaddons_is_pro' ) && ultraaddons_is_pro();
?>
<div class="ua-welcome-dashboard-wrapper">
    <!-- 1. Modern SaaS Hero Banner -->
    <section class="ua-welcome-hero-card">
        <div class="ua-hero-content">
            <div class="ua-hero-badge">
                <span class="ua-hero-badge-dot"></span>
                <span><?php echo esc_html__( 'The Ultimate Elementor Companion', 'ultraaddons-elementor-lite' ); ?></span>
            </div>
            <h1 class="ua-hero-title">
                <?php echo esc_html__( 'Build Better Websites,', 'ultraaddons-elementor-lite' ); ?>
                <span class="ua-text-gradient"><?php echo esc_html__( 'Faster & Smarter.', 'ultraaddons-elementor-lite' ); ?></span>
            </h1>
            <p class="ua-hero-subtitle">
                <?php echo esc_html__( 'Equip your Elementor editor with 60+ creative widgets, 15+ performance extensions, and a complete Header & Footer builder. Modular, lightweight, and built for speed.', 'ultraaddons-elementor-lite' ); ?>
            </p>
            <div class="ua-hero-actions">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ultraaddons-widgets' ) ); ?>" class="ua-hero-btn ua-hero-btn-primary">
                    <span><?php echo esc_html__( 'Explore Elements', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ultraaddons-extensions' ) ); ?>" class="ua-hero-btn ua-hero-btn-secondary">
                    <span><?php echo esc_html__( 'Browse Extensions', 'ultraaddons-elementor-lite' ); ?></span>
                </a>
                <a href="https://ultraaddons.com/widget/" target="_blank" class="ua-hero-btn ua-hero-btn-ghost">
                    <span><?php echo esc_html__( 'Documentation', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
                <?php if ( ! $is_pro ) : ?>
                    <a href="https://ultraaddons.com/pricing/" target="_blank" class="ua-hero-btn ua-hero-btn-pro">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        <span><?php echo esc_html__( 'Get UltraAddons PRO', 'ultraaddons-elementor-lite' ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- 2. Live Stats Highlights Grid -->
    <section class="ua-stats-row">
        <div class="ua-stat-card">
            <div class="ua-stat-icon ua-icon-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <div class="ua-stat-info">
                <div class="ua-stat-number">60+</div>
                <div class="ua-stat-title"><?php echo esc_html__( 'Creative Widgets', 'ultraaddons-elementor-lite' ); ?></div>
                <div class="ua-stat-desc"><?php echo esc_html__( 'Sliders, galleries, timelines & more', 'ultraaddons-elementor-lite' ); ?></div>
            </div>
        </div>

        <div class="ua-stat-card">
            <div class="ua-stat-icon ua-icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <div class="ua-stat-info">
                <div class="ua-stat-number">15+</div>
                <div class="ua-stat-title"><?php echo esc_html__( 'Powerful Extensions', 'ultraaddons-elementor-lite' ); ?></div>
                <div class="ua-stat-desc"><?php echo esc_html__( 'Sticky effects, CSS wrappers & tools', 'ultraaddons-elementor-lite' ); ?></div>
            </div>
        </div>

        <div class="ua-stat-card">
            <div class="ua-stat-icon ua-icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>
            </div>
            <div class="ua-stat-info">
                <div class="ua-stat-number"><?php echo esc_html__( 'Theme Builder', 'ultraaddons-elementor-lite' ); ?></div>
                <div class="ua-stat-title"><?php echo esc_html__( 'Header & Footer', 'ultraaddons-elementor-lite' ); ?></div>
                <div class="ua-stat-desc"><?php echo esc_html__( 'Build custom templates easily', 'ultraaddons-elementor-lite' ); ?></div>
            </div>
        </div>

        <div class="ua-stat-card">
            <div class="ua-stat-icon ua-icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            </div>
            <div class="ua-stat-info">
                <div class="ua-stat-number">100%</div>
                <div class="ua-stat-title"><?php echo esc_html__( 'Modular & Fast', 'ultraaddons-elementor-lite' ); ?></div>
                <div class="ua-stat-desc"><?php echo esc_html__( 'Enable only what you need', 'ultraaddons-elementor-lite' ); ?></div>
            </div>
        </div>
    </section>

    <!-- 3. Core Modules Quick Access Grid -->
    <section class="ua-modules-section">
        <div class="ua-section-heading">
            <h2 class="ua-section-title"><?php echo esc_html__( 'Explore Core Modules', 'ultraaddons-elementor-lite' ); ?></h2>
            <p class="ua-section-desc"><?php echo esc_html__( 'Customize your Elementor workflow by toggling specific features on or off.', 'ultraaddons-elementor-lite' ); ?></p>
        </div>

        <div class="ua-modules-grid">
            <div class="ua-module-card">
                <div class="ua-module-top">
                    <div class="ua-module-icon ua-icon-purple">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    </div>
                    <span class="ua-module-badge"><?php echo esc_html__( '60+ Items', 'ultraaddons-elementor-lite' ); ?></span>
                </div>
                <h3 class="ua-module-title"><?php echo esc_html__( 'Widgets Library', 'ultraaddons-elementor-lite' ); ?></h3>
                <p class="ua-module-desc"><?php echo esc_html__( 'Browse, search, and toggle any widget on or off with a single click to optimize page load speeds.', 'ultraaddons-elementor-lite' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ultraaddons-widgets' ) ); ?>" class="ua-module-link">
                    <span><?php echo esc_html__( 'Configure Widgets', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <div class="ua-module-card">
                <div class="ua-module-top">
                    <div class="ua-module-icon ua-icon-blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <span class="ua-module-badge"><?php echo esc_html__( '15+ Items', 'ultraaddons-elementor-lite' ); ?></span>
                </div>
                <h3 class="ua-module-title"><?php echo esc_html__( 'Extensions & Effects', 'ultraaddons-elementor-lite' ); ?></h3>
                <p class="ua-module-desc"><?php echo esc_html__( 'Supercharge native Elementor columns and sections with sticky effects, custom wrappers, and animations.', 'ultraaddons-elementor-lite' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ultraaddons-extensions' ) ); ?>" class="ua-module-link">
                    <span><?php echo esc_html__( 'Configure Extensions', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <div class="ua-module-card">
                <div class="ua-module-top">
                    <div class="ua-module-icon ua-icon-emerald">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>
                    </div>
                    <span class="ua-module-badge ua-badge-theme"><?php echo esc_html__( 'Builder', 'ultraaddons-elementor-lite' ); ?></span>
                </div>
                <h3 class="ua-module-title"><?php echo esc_html__( 'Header & Footer Builder', 'ultraaddons-elementor-lite' ); ?></h3>
                <p class="ua-module-desc"><?php echo esc_html__( 'Design fully responsive custom headers and footers using Elementor templates. Works with any theme.', 'ultraaddons-elementor-lite' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=header_footer_post' ) ); ?>" class="ua-module-link">
                    <span><?php echo esc_html__( 'Manage Templates', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. Resources & Support Hub -->
    <section class="ua-resources-section">
        <div class="ua-section-heading">
            <h2 class="ua-section-title"><?php echo esc_html__( 'Help, Guides & Support', 'ultraaddons-elementor-lite' ); ?></h2>
            <p class="ua-section-desc"><?php echo esc_html__( 'Need assistance or want to learn how to get the most out of UltraAddons?', 'ultraaddons-elementor-lite' ); ?></p>
        </div>

        <div class="ua-resources-grid">
            <a href="https://ultraaddons.com/widget/" target="_blank" class="ua-resource-card">
                <div class="ua-resource-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div class="ua-resource-text">
                    <h4><?php echo esc_html__( 'Documentation', 'ultraaddons-elementor-lite' ); ?></h4>
                    <p><?php echo esc_html__( 'Step-by-step guides and usage instructions for every widget.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </a>

            <a href="https://codeastrology.com/my-support/" target="_blank" class="ua-resource-card">
                <div class="ua-resource-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div class="ua-resource-text">
                    <h4><?php echo esc_html__( 'Dedicated Support', 'ultraaddons-elementor-lite' ); ?></h4>
                    <p><?php echo esc_html__( 'Reach our responsive support desk for rapid assistance.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </a>

            <a href="https://www.youtube.com/@codeastrology" target="_blank" class="ua-resource-card">
                <div class="ua-resource-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                </div>
                <div class="ua-resource-text">
                    <h4><?php echo esc_html__( 'Video Tutorials', 'ultraaddons-elementor-lite' ); ?></h4>
                    <p><?php echo esc_html__( 'Watch practical walk-throughs and design showcases.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </a>

            <a href="https://github.com/codersaiful/ultraaddons-elementor-lite/discussions" target="_blank" class="ua-resource-card">
                <div class="ua-resource-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
                <div class="ua-resource-text">
                    <h4><?php echo esc_html__( 'Request Features', 'ultraaddons-elementor-lite' ); ?></h4>
                    <p><?php echo esc_html__( 'Suggest new ideas and vote on features in our discussions.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </a>
        </div>
    </section>

    <!-- 5. Interactive FAQ Accordion -->
    <section class="ua-faq-container">
        <div class="ua-section-heading">
            <h2 class="ua-section-title"><?php echo esc_html__( 'Frequently Asked Questions', 'ultraaddons-elementor-lite' ); ?></h2>
            <p class="ua-section-desc"><?php echo esc_html__( 'Common questions and answers regarding UltraAddons.', 'ultraaddons-elementor-lite' ); ?></p>
        </div>

        <div class="ua-faq-list">
            <div class="ua-faq-accordion-item active">
                <div class="ua-faq-question">
                    <span><?php echo esc_html__( 'How do I turn on or off widgets to speed up my site?', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg class="ua-faq-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="ua-faq-answer" style="display: block;">
                    <p><?php echo esc_html__( 'Navigate to the Elements tab from the top navbar. You can search widgets by name, filter by category or Free/Pro, and toggle any widget on or off. Click "Save Changes" at the top or bottom to immediately optimize script loading on your frontend.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>

            <div class="ua-faq-accordion-item">
                <div class="ua-faq-question">
                    <span><?php echo esc_html__( 'Do I need Elementor Pro to use UltraAddons?', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg class="ua-faq-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="ua-faq-answer">
                    <p><?php echo esc_html__( 'No, UltraAddons works 100% smoothly with the free version of Elementor! You can build professional websites, headers, and footers without needing an Elementor Pro license.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>

            <div class="ua-faq-accordion-item">
                <div class="ua-faq-question">
                    <span><?php echo esc_html__( 'Can I create custom Headers and Footers for any WordPress theme?', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg class="ua-faq-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="ua-faq-answer">
                    <p><?php echo esc_html__( 'Yes! The Header & Footer Builder lets you design templates with Elementor and display them across your entire website, replacing or augmenting your theme headers seamlessly.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>

            <div class="ua-faq-accordion-item">
                <div class="ua-faq-question">
                    <span><?php echo esc_html__( 'Where can I get help or submit a feature suggestion?', 'ultraaddons-elementor-lite' ); ?></span>
                    <svg class="ua-faq-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="ua-faq-answer">
                    <p><?php echo esc_html__( 'Our dedicated support team is available via our CodeAstrology Support Desk, and you can submit feature requests or report issues directly on our public GitHub discussion board.', 'ultraaddons-elementor-lite' ); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Newsletter Subscription Card -->
    <section class="ua-newsletter-card">
        <div class="ua-newsletter-content">
            <h3 class="ua-newsletter-title"><?php echo esc_html__( 'Stay in the Loop', 'ultraaddons-elementor-lite' ); ?></h3>
            <p class="ua-newsletter-desc"><?php echo esc_html__( 'Subscribe to get product updates, tips, and exclusive offers delivered to your inbox.', 'ultraaddons-elementor-lite' ); ?></p>
            <div class="ua-newsletter-form-wrapper">
                <?php include_once 'includes/mailchimp-subscribe-form.php'; ?>
            </div>
        </div>
    </section>
</div>
