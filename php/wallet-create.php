<?php

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