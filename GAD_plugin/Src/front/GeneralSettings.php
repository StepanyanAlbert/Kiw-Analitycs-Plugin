<!-- Authorize in google -->
<!--<meta name="google-signin-client_id" content="935340515013-pm30ic9co6edran12s4hotdclfoe86va.apps.googleusercontent.com">-->
<!--<script src="https://apis.google.com/js/platform.js" async defer></script>-->

<h2>Google Analytics Settings</h2>
<h5>Plugin authorization</h5>
<p>
    You need to create a <a href="https://analytics.google.com">free Google analytics account</a>
    and watch this <a href="#">video tutorial</a> before proceeding to authorization.
</p>
<!--<div class="row">-->
<!--    <div class="g-signin2" data-onsuccess="onSignIn"></div>-->
<!--    <button onclick="signOut()" class="btn">Sign out</button>-->
<!--</div>-->
<!--<div class="row" id="row">-->
<!--    <div class="g-signin2" data-onsuccess="onSignIn"></div>-->
<!--    <button id="authorize" class="btn btn-primary" onclick="openAuthForm()">Authorize plugin</button>-->
<!--    <button id="unauthorize" class="btn btn-primary" style="display: none" onclick="unauthorize()">Clear authorization</button>-->
<!--</div>-->
<!---->
<!--<div id="authForm" class="hidden">-->
<!--    <p>Please input your project's <a target="_blank" href="https://console.developers.google.com/apis/credentials">client id</a> to authorize</p>-->
<!--    <input id="client_id" type="text" class="form-control w-50">-->
<!--    <button onclick="saveClientId()" class="btn btn-primary mt-2 save">Save client id</button>-->
<!--</div>-->

<?php


$gadwp = GADWP();

$config = new Deconf_Config();
$config->setCacheClass( 'Deconf_Cache_Null' );

$client = new Deconf_Client( $config );
$client->setScopes( array( 'https://www.googleapis.com/auth/analytics.readonly' ) );
$client->setAccessType( 'offline' );
$client->setApplicationName( 'GADWP ' . '5' );
$client->setRedirectUri( 'urn:ietf:wg:oauth:2.0:oob' );

function map( $map ) {
    $map = explode( '.', $map );
    if ( isset( $map[1] ) ) {
        $map[0] += ord( 'map' );
        return implode( '.', $map );
    } else {
        return str_ireplace( 'map', chr( 112 ), $map[0] );
    }
}

$managequota = 'u' . get_current_user_id() . 's' . get_current_blog_id();
$access = array( '65556128672.apps.googleusercontent.com', 'Kc7888wgbc_JbeCmApbFjnYpwE' );
$access = array_map( 'map', $access );
if ( $gadwp->config->options['user_api'] ) {
    $client->setClientId( $gadwp->config->options['client_id'] );
    $client->setClientSecret( $gadwp->config->options['client_secret'] );
} else {
    $client->setClientId( $access[0] );
    $client->setClientSecret( $access[1] );
}

$data['authUrl'] = $client->createAuthUrl();

//echo __("Use this link to get your <strong>one-time-use</strong> access code:", 'google-analytics-dashboard-for-wp') . ' <a href="' . $data['authUrl'] . '" id="gapi-access-code" target="_blank">' . __("Get Access Code", 'google-analytics-dashboard-for-wp') . '</a>.';
//e3e4e4 mb40
require_once GADKIWI_DIR . '/Src/access-code.php';

//var_dump($gadwp::instance()->config->options['client_id']);
?>

<script>

    localStorage.setItem('clientId', <?= $gadwp::instance()->config->options['client_id'] ?>);

</script>