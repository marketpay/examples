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

// CREAR USUARIO NUEVO
try
{
    $usersApi = new Swagger\Client\Api\UsersApi($apiClient);
    $user = new Swagger\Client\Model\UserNaturalPost;

    $response = $usersApi->usersPostNatural($user);

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}

// CREAR WALLET
try
{
    $walletsApi = new Swagger\Client\Api\WalletsApi($apiClient);
    $wallet = new Swagger\Client\Model\WalletPost([
        'owners'      => [ 6201 ],
        'description' => "User #6201 wallet",
        'currency'    => 'EUR'
    ]);

    $response = $walletsApi->walletsPost($wallet);

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}

// CREAR PAYIN
try
{
    $payInApi = new Swagger\Client\Api\PayInsRedsysApi($apiClient);

    $fund = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 2000
    ]);

    $fees = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 0
    ]);

    $payIn = new Swagger\Client\Model\RedsysPayByWebPost([
        'tag' => 'string',
        'save_card' => false,
        'card_id' => null,
        'credited_wallet_id' => 6209,
        'statement_descriptor' => 'Test Name',
        'url_ok' => 'http://<mydomain>/ok',
        'url_ko' => 'http://<mydomain>/ko',
        'debited_funds' => $fund,
        'fees' => $fees
    ]);

    $response = $payInApi->payInsRedsysRedsysPostPaymentByWeb($payIn);

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}