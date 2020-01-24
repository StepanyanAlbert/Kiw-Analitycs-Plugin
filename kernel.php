<?php

/**
 * Load the Admin Scripts and Styles
 */
function enqueue_admin_scraping_styles_and_scripts()
{
    wp_enqueue_script('boot2', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', ['jquery'], '', true);
    wp_enqueue_script('boot3', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', ['jquery'], '', true);
    wp_enqueue_script('boot4', 'https://code.jquery.com/jquery-3.4.1.slim.min.js', ['jquery'], '', true);
    wp_enqueue_style('bootstrap3', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
    wp_enqueue_style('fonts', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scraping_styles_and_scripts');

function admin_scraping_menu_init(){
    add_menu_page(
        'Dashboard',
        'KIWI_GAD',
        'administrator',
        'google-analytics-dashboard-settings',
        'init_settings_page',
        'dashicons-media-text'
    );
}
add_action('admin_menu', 'admin_scraping_menu_init');

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
    wp_add_dashboard_widget('custom_help_widget', 'Google Analytics Dashboard (KIWI)', 'custom_dashboard_help');
}

function custom_dashboard_help() {
    include 'HelloAnalytics.php';
}

function init_settings_page()
{
    require_once 'GeneralSettings.php';
}

