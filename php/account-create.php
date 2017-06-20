<?php

// CREAR ACCOUNT
try
{
    $usersApi = new Swagger\Client\Api\UsersApi($apiClient);
    $account = new Swagger\Client\Model\BankAccountIbanPost([
        'iban'          => 'ES2021......089213',
        'owner_name'    => 'Joe Bloggs',
        'owner_address' => new Swagger\Client\Model\Address([
            'address_line1' => 'Address line 1',
            'address_line2' => 'Address line 2',
            'city'          => 'City',
            'region'        => 'string',
            'postal_code'   => '11222',
            'country'       => 'FR'
        ])
    ]);

    $response = $usersApi->usersPostBankAccountIban(
        $user->getId(), // user-create.php
        $account
    );

    /* ... */
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}