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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('GADKIWI_DIR', __DIR__ . '/');


require_once __DIR__ . '/Src/Client.php';
require_once __DIR__ . '/Src/class-am-notification.php';
require_once __DIR__ . '/Src/config.php';
require_once __DIR__ . '/Src/Config.php';
require_once __DIR__ . '/Src/Client.php';
require_once __DIR__ . '/Src/Abstract.php';
require_once __DIR__ . '/Src/OAuth2.php';
require_once __DIR__ . '/Src/gadwp.php';
require_once 'kernel.php';
