<?php

/**
 * Load the Admin Scripts and Styles
 */
function enqueue_admin_gad_styles_and_scripts()
{
    wp_enqueue_script('boot2', kiwi_plugin_url( 'src/js/popper.min.js' ), array(), GADKIWI_VERSION, true );
    wp_enqueue_script('boot3', kiwi_plugin_url( 'src/js/bootstrap.min.js' ), array(), GADKIWI_VERSION, true );
    wp_enqueue_script('boot4', kiwi_plugin_url( 'src/js/jquery-3.4.1.slim.min.js' ), array(), GADKIWI_VERSION, true );
    wp_enqueue_script('boot5', kiwi_plugin_url( 'src/js/loader.js' ), array(), GADKIWI_VERSION, true );
    wp_enqueue_style('bootstrap1', kiwi_plugin_url( 'src/css/bootstrap.min.css' ));
    wp_enqueue_style('bootstrap2', kiwi_plugin_url( 'src/css/chartjs-visualizations.css' ));
    wp_enqueue_style('bootstrap3', kiwi_plugin_url( 'src/css/style.css' ));
    wp_register_style( 'fontawesome', 'http:////maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'fontawesome');
}
add_action( 'wp_dashboard_setup', 'my_custom_dashboard_widgets' );
add_action( 'admin_enqueue_scripts', 'enqueue_admin_gad_styles_and_scripts' );
add_action( 'admin_menu', 'admin_gad_menu_init' );
register_activation_hook(GADKIWI_FILE, 'my_plugin_activate');
add_action('admin_init', 'my_plugin_redirect');

function my_plugin_activate() {
    add_option('my_plugin_do_activation_redirect', true);
}

function my_plugin_redirect() {
    if (get_option('my_plugin_do_activation_redirect', false)) {
        delete_option('my_plugin_do_activation_redirect');
        wp_redirect('admin.php?page=google-analytics-dashboard-settings');
    }
}

function admin_gad_menu_init() {
    add_menu_page(
        'Dashboard',
        'KIWI_GAD',
        'administrator',
        'google-analytics-dashboard-settings',
        'go_to_dashboard',
        '<i class="fab fa-asymmetrik"></i>'
    );
}

function my_custom_dashboard_widgets() {
    wp_add_dashboard_widget( 'custom_help_widget', 'Google Analytics Dashboard (KIWI)', 'custom_dashboard_help' );
}

function kiwi_plugin_url( $path = '' ) {
    $url = plugins_url( $path, GADKIWI_DIR . 'Kiw-Analytics-Plugin/' );
    if ( is_ssl()
        and 'http:' == substr( $url, 0, 5 ) ) {
        $url = 'https:' . substr( $url, 5 );
    }
    return $url;
}

function custom_dashboard_help() {
    require_once 'HelloAnalytics.php';
}

function go_to_dashboard() {
    require_once 'GeneralSettings.php';
}

//$url = 'https://gadwp.exactmetrics.com/gadwp-revoke.php';
//https://accounts.google.com/o/oauth2/token', GADWP_ENDPOINT_URL . 'gadwp-token.php
//$response = wp_remote_post( $url, array(
//    'method' => 'POST',
//    'timeout' => 45,
//    'redirection' => 5,
//    'httpversion' => '1.0',
//    'blocking' => true,
//    'headers' => array(),
//    'body' => array( 'error_report' => '$error_report' ),
//    'cookies' => array()
//));
//var_dump( json_encode($response));die;
