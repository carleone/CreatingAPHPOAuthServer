<?php
require_once '../../vendor/autoload.php';

define('OAUTH_HOST', 'http://' . $_SERVER['SERVER_NAME']);
$id = 1;

// Init the OAuthStore
$options = array(
    'consumer_key' => '<MYCONSUMERKEY>',
    'consumer_secret' => '<MYCONSUMERSECRET>',
    'server_uri' => OAUTH_HOST,
    'request_token_uri' => OAUTH_HOST . '/request_token.php',
    'authorize_uri' => OAUTH_HOST . '/login.php',
    'access_token_uri' => OAUTH_HOST . '/access_token.php'
);
OAuthStore::instance('Session', $options);

if (empty($_GET['oauth_token'])) {
    // get a request token
    $tokenResultParams = OauthRequester::requestRequestToken($options['consumer_key'], $id);

    header('Location: ' . $options['authorize_uri'] .
        '?oauth_token=' . $tokenResultParams['token'] . 
        '&oauth_callback=' . urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
}
else {
    // get an access token
    $oauthToken = $_GET['oauth_token'];
    $tokenResultParams = $_GET;
    OAuthRequester::requestAccessToken($options['consumer_key'], $tokenResultParams['oauth_token'], $id, 'POST', $_GET);
    $request = new OAuthRequester(OAUTH_HOST . '/test_request.php', 'GET', $tokenResultParams);
    $result = $request->doRequest(0);
    if ($result['code'] == 200) {
        var_dump($result['body']);
    }
    else {
        echo 'Error';
    }
}
