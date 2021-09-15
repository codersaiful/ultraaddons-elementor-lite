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
                        <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                        <div class="btn-wrapper">
                            <a href="#" class="ua-button button"><?php echo esc_html__( 'Documentation', 'ultraaddons' ); ?></a>
                        </div>
                    </div>
                </div>
                <div class="info-box">
                    <div class="icon-wrapper">
                        <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg-icon/headphones.svg' ); ?>" alt="" class="icon" />
                    </div>
                    <div class="info">
                        <h3><?php echo esc_html__( 'Do you have any query?', 'ultraaddons' ); ?></h3>
                        <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                        <div class="btn-wrapper">
                            <a href="#" class="ua-button button"><?php echo esc_html__( 'Contact Support', 'ultraaddons' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="ua-section pr2">
            <div class="inner-wrapper">
                <div class="full-width info-box" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-1.svg' ); ?>);">
                    <h3 class="big"><?php echo esc_html__( 'Missing Any Features?', 'ultraaddons' ); ?></h3>
                    <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                    <div class="btn-wrapper">
                        <a href="#" class="ua-button button"><?php echo esc_html__( 'Request For Features', 'ultraaddons' ); ?></a>
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
                        <li data-target="support-policy"><?php echo esc_html__( 'Support Policy', 'ultraaddons' ); ?></li>
                        <li data-target="guideline"><?php echo esc_html__( 'User Guideline', 'ultraaddons' ); ?></li>
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
                                <h4>How to install Woo Product Table Pro Plugin?</h4>
                                <div class="answer">
                                    <p>You can create a beautiful and user friendly product table very easily. Our product table provides many more features like drag and drop elements, Sorting table data, advance search option. Also have responsive layout, pagination and third party plugin supports. You can easily change table design as you want.</p>
                                </div>
                            </li>
                            <li class="faq-item" id="installation-item-3">
                                <h4>How to create WooCommerce Product Table?</h4>
                                <div class="answer">
                                    <p>Install Woo Product Table<br>To get help on how to install Woo Product Table read this. <br>Click on Add New to create a new product table<br>From WordPress admin area go to PRODUCT TABLE > Add New to create a new Product Table.</p>
                                </div>
                            </li>
                            <li class="faq-item" id="installation-item-4">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user4?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                        </ul>
                    </div>
                    <div id="docs" class="faq-inner-box">
                        <ul>
                            <li class="faq-item active" id="installation-item-1">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user1?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-2">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user2?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-3">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user3?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-4">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user4?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                        </ul>
                    </div>
                    <div id="support-policy" class="faq-inner-box">
                        <ul>
                            <li class="faq-item active" id="installation-item-1">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user1?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-2">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user2?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-3">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user3?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-4">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user4?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                        </ul>
                    </div>
                    <div id="guideline" class="faq-inner-box">
                        <ul>
                            <li class="faq-item active" id="installation-item-1">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user1?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-2">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user2?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-3">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user3?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-4">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user4?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
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
                <div class="video-gallery">
                    <div class="video-tutorial">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/9hoFP0dgRx0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <h4 class="video-title"><?php echo esc_html__( 'React Native Profile Screen | React Native Profile UI', 'ultraaddons' ); ?></h4> 
                    </div>
                    <div class="video-tutorial">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/9hoFP0dgRx0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <h4 class="video-title"><?php echo esc_html__( 'React Native Profile Screen | React Native Profile UI', 'ultraaddons' ); ?></h4> 
                    </div>
                    <div class="video-tutorial">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/9hoFP0dgRx0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <h4 class="video-title"><?php echo esc_html__( 'React Native Profile Screen | React Native Profile UI', 'ultraaddons' ); ?></h4> 
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
                    <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                    <div class="btn-wrapper">
                        <a href="#" class="ua-button button"><?php echo esc_html__( 'Rating us', 'ultraaddons' ); ?></a>    
                    </div>
                    <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-2.svg' ); ?>" alt="">
                </div>
            </div>
        </section>

    </div>
        
