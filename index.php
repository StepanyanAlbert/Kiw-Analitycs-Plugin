<?php
/*
Plugin Name: Google Analytics Dashboard (KIWI)
Description: Display google analytics data in wp dashboard
Author: Kiwi Science
Version: v1.0.0
*/
const GAD_FILE = __FILE__;
if(!function_exists('plugin_dir_path')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
}

require_once 'kernel.php';
