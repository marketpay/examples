<?php

// CREAR TRANSFER NUEVA
try
{
    $transfersApi = new Swagger\Client\Api\TransfersApi($apiClient);

    $funds = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 2000
    ]);

    $fees = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 0
    ]);

    $transfer = new Swagger\Client\Model\TransferPost([
        'author_id' => $debitedUser->getId(), // user-create.php
        'debited_wallet_id' => $debitedWallet->getId(), // wallet-create.php
        'credited_user_id' => $creditedUser->getId(), // user-create.php
        'credited_wallet_id' => $creditedWallet->getId(), // wallet-create.php
        'debited_funds' => $funds,
        'fees' => $fees
    ]);

    $response = $transfersApi->transfersPost($transfer);

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}