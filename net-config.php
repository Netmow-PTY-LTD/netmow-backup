<?php

$db_values = get_option( 'netmow_google_keys' );

$clientid = '';
$clientsec = '';
$redirecturl = '';

if( $db_values ) {
    $clientid = $db_values['clientid'] ? $db_values['clientid'] : '';
    $clientsec = $db_values['clientsec'] ? $db_values['clientsec'] : '';
    $redirecturl = $db_values['redirecturl'] ? $db_values['redirecturl'] : '';
require_once WP_PLUGIN_DIR . '/netmow-backup/google-api-php-client/vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId($clientid );

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret($clientsec);

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri($redirecturl);

//
$google_client->addScope('email');

$google_client->addScope('profile');

$google_client->addScope('https://www.googleapis.com/auth/drive');



}