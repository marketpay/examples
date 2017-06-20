<?php

// CREAR PAYOUT
try
{
    $payOutsApi = new Swagger\Client\Api\PayOutsApi\PayOutsApi($apiClient);

    $funds = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 2000
    ]);

    $fees = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 0
    ]);

    $payOut = new Swagger\Client\Model\PayOutBankWirePost([
        'author_id'         => $user->getId(), // user-create.php
        'bank_account_id'   => $account->getId(), // account-create.php
        'debited_wallet_id' => $wallet->getId(), // wallet-create.php

        'debited_funds' => $funds,

        'fees' => $fees
    ]);

    $response = $payOutsApi->payOutsPost($payOut);

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}
