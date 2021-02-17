<?php

use UltraAddons\Core\Widgets_Manager;

defined( 'ABSPATH' ) || die();

$widgets = Widgets_Manager::widgets();

var_dump( $widgets );
?>
<h1>Widgets</h1>