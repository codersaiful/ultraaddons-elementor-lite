<?php

defined( 'ABSPATH' ) || die();
$full_logo_image = ULTRA_ADDONS_ASSETS . 'images/svg/full-color-logo.svg';
?>
<div class="wrap about-wrap ultraaddons-wrap">
    <h1 class="ultraaddons-color-heading"><?php _e('Welcome to UltraAddons', 'ultraaddons'); ?></h1>
    
    <div class="ultraaddons-system-stats">
        <img src="<?php echo esc_attr( $full_logo_image ); ?>" style="height: 176px;width: auto;">
        <p class="ultraaddons-info"><?php esc_html_e('In elementor Page Builder, you will get a Category name [Addons - UltraAddons]. Choose your Widget.', 'ultraaddons'); ?></p>
        <h3><?php esc_html_e('Available Widgets and Features', 'ultraaddons'); ?></h3>

        <table class="system-status-table">
            <tbody>
                <tr>
                    <td><?php esc_html_e('Name', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Type', 'ultraaddons'); ?></td>
                </tr>

                <tr>
                    <td><?php esc_html_e('Slider', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Widget', 'ultraaddons'); ?></td>
                </tr>

                
                <tr>
                    <td><?php esc_html_e('Count Down Timer', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Widget', 'ultraaddons'); ?></td>
                </tr>
                
                <tr>
                    <td><?php esc_html_e('Advance Heading', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Widget', 'ultraaddons'); ?></td>
                </tr>

                
                <tr>
                    <td><?php esc_html_e('Advance List', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Widget', 'ultraaddons'); ?></td>
                </tr>

                
                <tr>
                    <td><?php esc_html_e('Button', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Widget', 'ultraaddons'); ?></td>
                </tr>

                
                
                <tr>
                    <td><?php esc_html_e('Info Box', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Widget', 'ultraaddons'); ?></td>
                </tr>

                
                
                
                <tr>
                    <td><?php esc_html_e('Wrapper Link', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Section Effect', 'ultraaddons'); ?></td>
                </tr>

                
                
                <tr>
                    <td><?php esc_html_e('Hover Animation', 'ultraaddons'); ?></td>
                    <td><?php esc_html_e('Any Content', 'ultraaddons'); ?></td>
                </tr>

                
                
            </tbody>
        </table>
    </div>
</div>