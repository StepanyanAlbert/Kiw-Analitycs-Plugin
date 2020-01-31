<?php
/*
Plugin Name: Google Analytics Dashboard (KIWI)
Description: Display google analytics data in wp dashboard
Author: Kiwi Science
Version: v1.0.0
*/

if(!function_exists( 'plugin_dir_path' )) {
    require_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/wp-load.php';
}
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

define( 'GADKIWI_DIR', __DIR__ . '/' );
define( 'GADKIWI_FILE', __FILE__ );
define( 'GADKIWI_VERSION', '1.0.0' );

require_once 'kernel.php';
