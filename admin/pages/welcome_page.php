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
                        <a href="#" class="ua-button button"><?php echo esc_html__( 'Documentation', 'ultraaddons' ); ?></a>
                    </div>
                </div>
                <div class="info-box">
                    <div class="icon-wrapper">
                        <img src="<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg-icon/headphones.svg' ); ?>" alt="" class="icon" />
                    </div>
                    <div class="info">
                        <h3><?php echo esc_html__( 'Do you have any query?', 'ultraaddons' ); ?></h3>
                        <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                        <a href="#" class="ua-button button"><?php echo esc_html__( 'Contact Support', 'ultraaddons' ); ?></a>
                    </div>
                </div>
            </div>

        </section>

        <section class="ua-section pr2">
            <div class="inner-wrapper">
                <div class="full-width info-box" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-1.svg' ); ?>);">
                    <h3 class="big"><?php echo esc_html__( 'Missing Any Features?', 'ultraaddons' ); ?></h3>
                    <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                    <a href="#" class="ua-button button"><?php echo esc_html__( 'Request For Features', 'ultraaddons' ); ?></a>
                </div>
            </div>
        </section>

        <section class="ua-section pr3 faq">
            <div class="inner-wrapper">
                <div class="faq-nav">
                    <ul>
                        <li class="active" data-target="installation"><?php echo esc_html__( 'Installation', 'ultraaddons' ); ?></li>
                        <li data-target="docs"><?php echo esc_html__( 'Documentation', 'ultraaddons' ); ?></li>
                        <li data-target="support-policy"><?php echo esc_html__( 'Support Policy', 'ultraaddons' ); ?></li>
                        <li data-target="guideline"><?php echo esc_html__( 'User Guideline', 'ultraaddons' ); ?></li>
                    </ul>
                </div>
                <div class="faq-details">
                    <h3 class="big"><?php echo esc_html__( 'Frequently Asked Questions', 'ultraaddons' ); ?></h3>
                    <div id="installation" class="faq-inner-box">
                        <ul>
                            <li class="faq-item" id="installation-item-1">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-2">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-3">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                            <li class="faq-item" id="installation-item-4">
                                <h4><?php echo esc_html__( 'Is there any support policy available for user?', 'ultraaddons' ); ?></h4>
                                <div class="answer"><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></div>
                            </li>
                        </ul>
                    </div>
                    <div id="docs" class="faq-inner-box">

                    </div>
                    <div id="support-policy" class="faq-inner-box">

                    </div>
                    <div id="guideline" class="faq-inner-box">

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

        <section class="ua-section pr6">
            <div class="inner-wrapper">
                <div class="full-width info-box" style="background-image: url(<?php echo esc_url( ULTRA_ADDONS_ASSETS . 'images/svg/Illustration-2.svg' ); ?>);">
                    <h3 class="big"><?php echo esc_html__( 'Are you like our Product?', 'ultraaddons' ); ?></h3>
                    <p><?php echo esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words', 'ultraaddons' ); ?></p>
                    <a href="#" class="ua-button button"><?php echo esc_html__( 'Rating us', 'ultraaddons' ); ?></a>
                </div>
            </div>
        </section>

    </div>
        
