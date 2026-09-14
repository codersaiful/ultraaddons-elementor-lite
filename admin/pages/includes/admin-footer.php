<?php
defined( 'ABSPATH' ) || die();
?>
        </div> <!-- /.ua-main-body-content -->
        <footer class="ua-admin-footer">
            <div class="ua-footer-left">
                <span><?php esc_html_e( 'UltraAddons for Elementor', 'ultraaddons-elementor-lite' ); ?> · <?php esc_html_e( 'Crafted with ❤️ for WordPress creators.', 'ultraaddons-elementor-lite' ); ?></span>
            </div>
            <div class="ua-footer-right">
                <a href="https://ultraaddons.com/widget/" target="_blank"><?php esc_html_e( 'Documentation', 'ultraaddons-elementor-lite' ); ?></a>
                <span class="ua-footer-sep">·</span>
                <a href="https://codeastrology.com/my-support/" target="_blank"><?php esc_html_e( 'Support Desk', 'ultraaddons-elementor-lite' ); ?></a>
                <span class="ua-footer-sep">·</span>
                <a href="https://wordpress.org/support/plugin/ultraaddons-elementor-lite/reviews/#new-post" target="_blank" class="ua-footer-rate">★★★★★ <?php esc_html_e( 'Rate 5-Stars', 'ultraaddons-elementor-lite' ); ?></a>
            </div>
            <?php do_action( 'ultraaddons/admin/footer' ); ?>
        </footer>
    </div> <!-- /.ultraaddons-dashboard-area -->
</div><!-- /.ultraaddons-admin-wrapper -->