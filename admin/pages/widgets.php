<?php

use UltraAddons\Core\Widgets_Manager;

defined( 'ABSPATH' ) || die();

$widgets = Widgets_Manager::widgets();


?>

<div class="ultraaddons-section ua-widgets-wrapper">
    <div class="ua-section-inside">
        <div class="ua-header">
            <h1><?php echo esc_html__( 'Widgets', 'ultraaddons' ); ?></h1>
        </div>
        
        <div class="ua-sectioon-content">
            <div class="ua-content-inside">
                
                <form class="ua-widget-list-form" action="" method="post">
                    <?php 
                    foreach( $widgets as $widget ){
                        var_dump($widget);
                    ?>
                    <div class="ua-widget-item-wrappper">
                        
                    </div>
                    <?php } ?>
                </form>
                
            </div>
        </div>
    </div>
</div>