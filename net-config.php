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

$google_client->setAccessType('offline');

}


$gt_values = get_option( 'netmow_backup_google_account_data' );
$google_client->fetchAccessTokenWithRefreshToken($gt_values['g_access_token']);


if ($google_client->isAccessTokenExpired()) {
    echo '<div style="color: red">Exp</div>';
    
    $google_client->setAccessToken(get_option( 'netmow_backup_google_account_data' )['g_access_token']);

    $google_service = new Google_Service_Oauth2($google_client);
    $data = $google_service->userinfo->get();
    if (!empty($data["given_name"])) {
        $user_first_name = $data["given_name"];
    }
    if (!empty($data["family_name"])) {
        $user_last_name = $data["family_name"];
    }
    if (!empty($data["email"])) {
        $user_email_address = $data["email"];
    }
    if (!empty($data["gender"])) {
        $user_gender = $data["gender"];
    }
    if (!empty($data["picture"])) {
        $user_image = $data["picture"];
    }
    $gdata = array(
        'g_access_token'  => $token["access_token"],
        'user_first_name' => $user_first_name,
        'user_last_name' => $user_last_name,
        'user_email_address' => $user_email_address,
        'user_gender' => $user_gender,
        'user_image' => $user_image,
    );
    //entering data into options table
    update_option( 'netmow_backup_google_account_data', $gdata );

    echo '<h1>Auth Done From Google</h1>';
    $this->netmow_backup_redirect_to();



} else{
    echo '<div style="color: green">Not Exp</div>';
}

