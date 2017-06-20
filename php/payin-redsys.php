<?php

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
        'credited_wallet_id' => $wallet->getId(), // wallet-create.php
        'statement_descriptor' => 'Test Name',
        'url_ok' => 'http://api.lemonpay.me/ok',
        'url_ko' => 'http://api.lemonpay.me/ko',
        'debited_funds' => $fund,
        'fees' => $fees
    ]);

    $response = $payInApi->payInsRedsysRedsysPostPaymentByWeb($payIn);

    /* ... */
    
    // MONTAR UNA VISTA HTML CON EL FORMULARIO:
    ?>

    <form action="{{ $intent->getUrl() }}" method="POST">
        <input type="hidden" name="Ds_SignatureVersion" value="{{ $intent->getDsSignatureVersion() }}" />
        <input type="hidden" name="Ds_MerchantParameters" value="{{ $intent->getDsMerchantParameters() }}" />
        <input type="hidden" name="Ds_Signature" value="{{ $intent->getDsSignature() }}" />
    </form>

    <script>
        window.onload = function() {
            document.forms[0].submit();
        };
    </script>

    <?php
}
catch (Swagger\Client\ApiException $e)
{
    Log::error($e->getResponseObject());
}
