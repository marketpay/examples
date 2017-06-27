<?php

// CREAR PAYIN UNIVERSALPAY
try
{
    $universalPayApi = new PayInsUniversalPayApi($apiClient);

    $payin = new UniversalPayPayByWebPost([
        'debited_funds' => new Money([
            'amount' => 2000,
            'currency' => Money::CURRENCY_EUR
        ]),
        'fees' => new Money([
            'amount' => 1000,
            'currency' => Money::CURRENCY_EUR
        ]),
        'card_id' => 71077,
        'save_card' => true,
        'statement_descriptor' => 'Nombre Producto',
        'tag' => 'test-universal',
        'credited_wallet_id' => 9649,
        'secure_mode' => UniversalPayPayByWebPost::SECURE_MODE_DEFAULT,
        'success_url' => 'http://lemonpay.me/ok',
        'cancel_url' => 'http://lemonpay.me/ko',
        'language' => UniversalPayPayByWebPost::LANGUAGE_ES,
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

    $response = $universalPayApi->payInsUniversalPayUniversalPayPostPaymentByWeb($payin);

    /* ... */

}
catch (ApiException $e)
{
    Log::error($e->getResponseObject());
}
