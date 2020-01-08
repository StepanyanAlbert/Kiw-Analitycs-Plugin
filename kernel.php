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
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scraping_styles_and_scripts');

function admin_scraping_menu_init(){
    add_menu_page(
        'Dashboard',
        'KIWI_GAD',
        'administrator',
        'google-analytics-dashboard',
        'init_dashboard',
        'dashicons-media-text'
    );
    add_submenu_page(
        'google-analytics-dashboard',
        'Dashboard',
        'Dashboard',
        'administrator',
        'google-analytics-dashboard',
        'init_dashboard'
    );
    add_submenu_page(
        'google-analytics-dashboard',
        'Settings',
        'General Settings',
        'administrator',
        'google-analytics-dashboard-settings',
        'init_settings_page'
    );
//    add_submenu_page(
//        'KIWI_GAD',
//        'Settings',
//        'General Settings',
//        'administrator',
//        'google-analytics-dashboard-settings',
//        'init_settings_page'
//    );
}
add_action('admin_menu', 'admin_scraping_menu_init');

function init_dashboard()
{
    require_once 'Templates/HelloAnalytics.php';
}
function init_settings_page()
{
    require_once 'Templates/GeneralSettings.php';
}

