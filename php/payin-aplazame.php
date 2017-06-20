<?php

// CREAR PAYIN APLAZAME
try
{
    $aplazamePayInApi = new Swagger\Client\Api\PayInsAplazameApi($apiClient);

    $funds = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 2000
    ]);

    $fees = new Swagger\Client\Model\Money([
        'currency' => 'EUR',
        'amount' => 0
    ]);

    $reference = new Swagger\Client\Model\AplazamePayByWebPost([
        'user_id'            => $user->getId(), // user-create.php
        'credited_wallet_id' => $wallet->getId(), // wallet-create.php

        'cancel_url'  => $this->denied_url,
        'success_url' => $this->confirmed_url,

        'debited_funds' => $funds,

        'fees' => $fees,

        'order_items' => [
            new Swagger\Client\Model\AplazameOrderItem([
                'id'   => $product->id,
                'name' => $product->name,
                'url'  => $product->url,
                'image_url' => $product->image->url,
                'quantity'  => 1,
                'price'     => (int) bcmul($product->price, 100, 0)
            ])
        ],

        'customer' => new Swagger\Client\Model\Customer([
            'first_name' => $destination->name,
            'email' => $destination->email,
            'telephone' => new Swagger\Client\Model\Telephone([
                'country_code' => $destination->prefix,
                'number' => $destination->phone
            ]),
            'address' => new Swagger\Client\Model\Address([
                'address_line1' => $destination->line1,
                'address_line2' => $destination->line2,
                'city'    => $destination->city,
                'region'  => $destination->state,
                'country' => $destination->country,
                'postal_code' => $destination->zip_code
            ])
        ]),

        'shipping' => new Swagger\Client\Model\Money([
            'currency' => 'EUR',
            'amount'   => (int) bcmul($product->fee_shipping, 100, 0)
        ])
    ]);

    $response = $aplazamePayInApi->payInsAplazameAplazamePostPaymentByWeb($reference);

    /* ... */

    // MONTAR UNA VISTA HTML CON EL SCRIPT:
    ?>

    <script type="text/javascript" defer
        src="https://aplazame.com/static/aplazame.js"
        data-aplazame="{{ Config::get('services.aplazame.key') }}"
        data-version="1.2"
        data-sandbox="{{ Config::get('services.aplazame.sandbox') ? 'true' : 'false' }}"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            aplazame.checkout({!! $response->getCheckoutData() !!});
        });
    </script>

    <?php

}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}
