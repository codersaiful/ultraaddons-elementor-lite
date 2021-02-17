<?php

use UltraAddons\Core\Extensions_Manager;

defined( 'ABSPATH' ) || die();

$extension = Extensions_Manager::get_list();

var_dump( $extension );
?>
<h1>Extension</h1>