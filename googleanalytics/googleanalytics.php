<?php

/*
 * Plugin Name: Google Analytics Dashboard (by KIWI-SCIENCE)
 * Description: Use Google Analytics on your WordPress site without touching any code, and view visitor reports right in your WordPress admin dashboard!
 * Version: 1.0.0
 * Author: Kiwi-Science
 * Author URI: http://kiwi-science.com
 */

if ( !defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
}
if ( !defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}
if ( !defined( 'WP_PLUGIN_URL' ) ) {
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
}
if ( !defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
}
if ( !defined( 'GA_NAME' ) ) {
	define( 'GA_NAME', 'googleanalytics' );
}
if ( !defined( 'GA_PLUGIN_DIR' ) ) {
	define( 'GA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . GA_NAME );
}
if ( !defined( 'GA_PLUGIN_URL' ) ) {
	define( 'GA_PLUGIN_URL', WP_PLUGIN_URL . '/' . GA_NAME );
}
if ( !defined( 'GA_MAIN_FILE_PATH' ) ) {
	define( 'GA_MAIN_FILE_PATH', __FILE__ );
}
if ( !defined( 'GA_SHARETHIS_SCRIPTS_INCLUDED' ) ) {
	define( 'GA_SHARETHIS_SCRIPTS_INCLUDED', 0 );
}

define( 'GOOGLEANALYTICS_VERSION', '2.1.3' );
include_once GA_PLUGIN_DIR . '/overwrite/ga_overwrite.php';
include_once GA_PLUGIN_DIR . '/class/Ga_Autoloader.php';
include_once GA_PLUGIN_DIR . '/tools/class-support-logging.php';
Ga_Autoloader::register();
Ga_Hook::add_hooks( GA_MAIN_FILE_PATH );

add_action( 'plugins_loaded', 'Ga_Admin::loaded_googleanalytics' );
add_action( 'init', 'Ga_Helper::init' );
