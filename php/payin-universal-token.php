<?php

// CREAR TOKEN UNIVERSAL PAY
try
{
    $universalPayApi = new PayInsUniversalPayApi($apiClient);

    $payin = new UniversalPayTokenRequestPost([
        'authorization_funds' => new Money([
            'amount' => 1000,
            'currency' => Money::CURRENCY_EUR
        ]),
        'tag' => 'test-token',
        'credited_wallet_id' => 9649,
        'secure_mode' => UniversalPayTokenRequestPost::SECURE_MODE_DEFAULT,
        'success_url' => 'http://lemonpay.me/ok',
        'cancel_url' => 'http://lemonpay.me/ko',
        'language' => UniversalPayTokenRequestPost::LANGUAGE_ES,
        'customer' => new CustomerDetail([
            'first_name' => 'Lemon',
            'last_name' => 'Pay',
            'telephone' => new Telephone([
                'country_code' => '34',
                'number' => '666999666'
            ]),
            'address' => new Address([
                'address_line1' => 'Llacuna, 161',
                'address_line2' => '3 (Oficina Bandit)',
                'city' => 'Barcelona',
                'region' => 'Barcelona',
                'postal_code' => '08017',
                'country' => Address::COUNTRY_ES
            ])
        ])
    ]);

    $response = $universalPayApi->payInsUniversalPayUniversalPaySaveCard($payin);

    /* ... */

}
catch (ApiException $e)
{
    Log::error($e->getResponseObject());
}
