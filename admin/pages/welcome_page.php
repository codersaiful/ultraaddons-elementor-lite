<?php

defined( 'ABSPATH' ) || die();

?>
    <div class="ua-admin-welcome-content-area">
        <section class="welcome-banner" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/header-background.png' ); ?>);">
            <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/transparent-logo.png' ); ?>" alt="" class="logo" />
            <p class="greetings"><?php echo esc_html__( 'Thanks for choosing us!', 'ultraaddons' )?></p>
        </section>

        <section class="ua-section pr1">
            <div class="inner-wrapper">
                <div class="info-box">
                    <div class="icon-wrapper">
                        <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg-icon/file.svg' ); ?>" alt="" class="icon" />
                    </div>
                    <div class="info">
                        <h3><?php echo esc_html__( 'User Guidelines', 'ultraaddons' ); ?></h3>
                        <p><?php echo esc_html__( 'Our documentation page represents all of our products documentation in a single page. From this page select your product documentation for getting help.', 'ultraaddons' ); ?></p>
                        <div class="btn-wrapper">
                            <a href="https://ultraaddons.com/widget/" target="_blank" class="ua-button button"><?php echo esc_html__( 'Documentation', 'ultraaddons' ); ?></a>
                        </div>
                    </div>
                </div>
                <div class="info-box">
                    <div class="icon-wrapper">
                        <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg-icon/headphones.svg' ); ?>" alt="" class="icon" />
                    </div>
                    <div class="info">
                        <h3><?php echo esc_html__( 'Do you have any query?', 'ultraaddons' ); ?></h3>
                        <p><?php echo esc_html__( 'You can chat with our support agent by clicking the chat icon which appears on the right bottom of every page.', 'ultraaddons' ); ?></p>
                        <div class="btn-wrapper">
                            <a href="https://codeastrology.com/supports/" target="_blank" class="ua-button button"><?php echo esc_html__( 'Contact Support', 'ultraaddons' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="ua-section pr2">
            <div class="inner-wrapper">
                <div class="full-width info-box" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-1.svg' ); ?>);">
                    <h3 class="big"><?php echo esc_html__( 'Missing Any Features?', 'ultraaddons' ); ?></h3>
                    <p><?php echo esc_html__( 'Feel free contact us. If you need any new features on UltraAddons.', 'ultraaddons' ); ?></p>
                    <div class="btn-wrapper">
                        <a href="https://github.com/codersaiful/ultraaddons-elementor-lite/discussions/new" class="ua-button button" target="_blank"><?php echo esc_html__( 'Request For Features', 'ultraaddons' ); ?></a>
                    </div>
                    <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-1.svg' ); ?>" alt="">
                </div>
            </div>
        </section>

        <section class="ua-section pr3 faq">
            <div class="inner-wrapper">
                <div class="faq-nav">
                    <h3 class="big"><?php echo esc_html__( 'Frequently Asked Questions', 'ultraaddons' ); ?></h3>
                    <ul>
                        <li class="active" data-target="installation"><?php echo esc_html__( 'Installation', 'ultraaddons' ); ?></li>
                        <li data-target="docs"><?php echo esc_html__( 'Documentation', 'ultraaddons' ); ?></li>
                    </ul>
                </div>
                <div class="faq-details">
                    <h3 class="big"><?php echo esc_html__( 'Frequently Asked Questions', 'ultraaddons' ); ?></h3>
                    <div id="installation" class="faq-inner-box active">
                        <ul>
                            <li class="faq-item active" id="installation-item-1">
                                <h4><?php echo esc_html__( 'Where is my plugin purchase code?', 'ultraaddons' ); ?></h4>
                                <div class="answer">
                                    <p>You can find a license key inside your account on CodeCanyon.</p>
                                    <ol>
                                        <li>Make sure that you logged in to your account.</li>
                                        <li>Visit your Downloads section, find the plugin, that you want to get a license key for, and click the button Download, then License Certificate.</li>
                                        <li>Find the Item Purchase Code in the text document and paste it into the form inside the plugin.</li>
                                    </ol>

                                </div>
                            </li>
                            <li class="faq-item" id="installation-item-2">
                                <h4>Can I use this plugin for my Theme?</h4>
                                <div class="answer">
                                    <p>Yes.</p>
                                </div>
                            </li>
                            <li class="faq-item" id="installation-item-3">
                                <h4>Does this plugin support any theme?</h4>
                                <div class="answer">
                                    <p>Yes.</p>
                                </div>
                            </li>
                            <li class="faq-item" id="installation-item-5">
                                <h4><?php echo esc_html__( 'Does this plugin support customization by Filter or Action Hook?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'Yes, there are lot of filter and action hook available here. You can customize for your site even for your premium theme or plugin.', 'ultraaddons' ); ?></div>
                            </li>
                        </ul>
                    </div>
                    <div id="docs" class="faq-inner-box">
                        <ul>
                            <li class="faq-item active" id="installation-item-1">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user?', 'ultraaddons' ); ?></h4>
                                <div class="answer">Yes. Please check out the link: <a href="https://codeastrology.com/support-policy/">Support</a></div>
                            </li>
                            <li class="faq-item" id="installation-item-2">
                                <h4><?php echo esc_html__( 'What is the Requirements?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'UltraAddons Elementor Pro is an extension for Elementor Page Builder. To install you need UltraAddons Elementor Lite version. Check out this documentation to know how to install UltraAddons Elementor Pro.', 'ultraaddons' ); ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="ua-section pr4 video">
            <div class="inner-wrapper">
                <div class="title-wrapper">
                    <h3 class="big"><?php echo esc_html__( 'Video Tutorials', 'ultraaddons' ); ?></h3>
                    <div class="button-wrapper">
                        <a href="#" class="ua-button button"><?php echo esc_html__( 'Our Video Playlist', 'ultraaddons' ); ?></a>
                    </div>
                </div>
                <div class="video-gallery owl-carousel">
                    <div class="video-tutorial">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/JDjANJzWKTA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <h4 class="video-title"><?php echo esc_html__( 'Ultra Addons - #1 Best Elementor addons WordPress plugin Unlimited Template and Free for all', 'ultraaddons' ); ?></h4> 
                    </div>
                    <div class="video-tutorial">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/n_ea3devnlg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <h4 class="video-title"><?php echo esc_html__( 'CodeAstrology', 'ultraaddons' ); ?></h4> 
                    </div>
                    <div class="video-tutorial">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/jJ8U7h2Q8HU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <h4 class="video-title"><?php echo esc_html__( 'Code Astrology Latest Products for WordPress, Elementor, WooCommerce etc.', 'ultraaddons' ); ?></h4> 
                    </div>
                    
                </div>
            </div>
        </section>

        <section class="ua-section pr5 newsletter">
            <div class="inner-wrapper">
                <div class="full-width" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/newsletter-bg.png' ); ?>);">
                    <h3 class="big"><?php echo esc_html__( 'Subscribe Newsletter', 'ultraaddons' ); ?></h3>
                    <p><?php echo esc_html__( 'To get updated news, current offers, deals and tips please subscribe
to our newsletter.', 'ultraaddons' ); ?></p>
                    <div class="mc4-form">
                        <?php include_once 'includes/mailchimp-subscribe-form.php'; ?>
                    </div>
                </div>
            </div>

        </section>

        <section class="ua-section pr6 rating">
            <div class="inner-wrapper">
                <div class="full-width info-box" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-2.svg' ); ?>);">
                    <h3 class="big"><?php echo esc_html__( 'Are you like our Product?', 'ultraaddons' ); ?></h3>
                    <p><?php echo esc_html__( ' Thanks for your recent visit to our plugin. We want to provide the best experience possible! To help us, please take a moment to leave your feedback. Thank you', 'ultraaddons' ); ?></p>
                    <div class="btn-wrapper">
                        <a href="https://wordpress.org/support/plugin/ultraaddons-elementor-lite/reviews/?filter=5" class="ua-button button" target="_blank"><?php echo esc_html__( 'Rating us', 'ultraaddons' ); ?></a>    
                    </div>
                    <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-2.svg' ); ?>" alt="">
                </div>
            </div>
        </section>

    </div>
        
