<?php

// CONFIGURACIÓN CLIENTE
$MARKETPAY_KEY = "...";
$MARKETPAY_SECRET = "...";
$MARKETPAY_DOMAIN = "https://api-sandbox.marketpay.io";

$config = new Swagger\Client\Configuration;

$config->setHost($MARKETPAY_DOMAIN);
$config->setApiKey($MARKETPAY_KEY, $MARKETPAY_SECRET);
$config->setDebug(false);

// OBTENER OAUTH
$oauthClient = new Swagger\Client\ApiClient($config);

list($response, $statusCode, $httpHeader) = $oauthClient->callApi('/v2.01/oauth/token', 'POST', null, [
    'grant_type' => 'client_credentials'
], [
    'Host' => str_replace('https://', '', $MARKETPAY_DOMAIN), // == api-sandbox.marketpay.io,
    'Content-Type' => 'application/x-www-form-urlencoded',
    'Authorization' => 'Basic ' . base64_encode($MARKETPAY_KEY . ':' . $MARKETPAY_SECRET)
]);

$config->setAccessToken($response->access_token);

// GLOBAL API CLIENT
$apiClient = new Swagger\Client\ApiClient($config);