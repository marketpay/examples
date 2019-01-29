<?php

require('./vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use GuzzleHttp\Client;
use MarketPay\ApiException;
use MarketPay\Configuration;
use MarketPay\Api\UsersApi;
use MarketPay\Api\WalletsApi;
use MarketPay\Api\PayInsBankwireApi;
use MarketPay\Api\PayOutsBankwireApi;
use MarketPay\Model\Money;
use MarketPay\Model\Address;
use MarketPay\Model\WalletPost;
use MarketPay\Model\UserLegalPost;
use MarketPay\Model\UserNaturalPost;
use MarketPay\Model\PayInBankwirePost;
use MarketPay\Model\PayOutBankWirePost;
use MarketPay\Model\BankAccountIbanPost;

// CONFIGURACIÓN CLIENTE
$MARKETPAY_KEY = "...";
$MARKETPAY_SECRET = "...";
$MARKETPAY_DOMAIN = "https://api-sandbox.marketpay.io";

// 1. OBTENER OAUTH
try
{
    $response = (new Client)->post($MARKETPAY_DOMAIN . '/v2.01/oauth/token', [
        'form_params' => [
            'grant_type' => 'client_credentials'
        ],
        'headers' => [
            'Host' => str_replace('https://', '', $MARKETPAY_DOMAIN),
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode(
                $MARKETPAY_KEY . ':' . $MARKETPAY_SECRET
            )
        ]
    ]);

    $response = json_decode((string) $response->getBody());

    dump($response);
}
catch (Exception $e)
{
    dump($e);
}

// Inicializar Configuration
$config = new Configuration;
$config->setHost($MARKETPAY_DOMAIN);
$config->setApiKey($MARKETPAY_KEY, $MARKETPAY_SECRET);
$config->setDebug(false);
$config->setAccessToken($response->access_token);
$config->setDefaultConfiguration($config);

// 2. CREAR USUARIO NUEVO
try
{
    $usersApi = new UsersApi(new Client, $config);
    $user = new UserNaturalPost;
    // user = new UserLegalPost; Para usuario Legal

    $userResponse = $usersApi->usersPostNatural($user);

    // Guardar el $userResponse->getId();
    /* ... */
    dump($userResponse);
}
catch (Exception $e)
{
    // Ha habido un error creando el usuario
    dump($e);
}

// 3. CREAR WALLET
try
{
    $walletsApi = new WalletsApi(new Client, $config);
    $wallet = new WalletPost([
        'owners' => [ $userResponse->getId() ], // ID del usuario
        'description' => "User #{$userResponse->getId()} wallet", // Descripcion libre
        'currency' => 'EUR'
    ]);

    $walletResponse = $walletsApi->walletsPost($wallet);

    // Guardar el $walletResponse->getId();
    /* ... */
    dump($walletResponse);
}
catch (Exception $e)
{
    // Ha habido un error creando el wallet
    dump($e);
}

// 4. CREAR UN PAYIN DEL TIPO BANKWIRE
try
{
    $bankwireApi = new PayInsBankwireApi(new Client, $config);
    $bankwire = new PayInBankwirePost([
        'credited_wallet_id' => $walletResponse->getId(),
        'debited_funds' => new Money([
            'amount' => 100,
            'currency' => 'EUR'
        ]),
        'fees' => new Money([
            'amount' => 0,
            'currency' => 'EUR',
        ])
    ]);

    $bankwireResponse = $bankwireApi->payInsBankwireBankwirePaymentByDirect($bankwire);

    // Guardar el $bankwireResponse->getId();
    /* ... */
    dump($bankwireResponse);
}
catch (Exception $e)
{
    // Ha habido un error creando el bankwire
    dump($e);
}

// 6. CREAR UN ACCOUNT
try
{
    $usersApi = new UsersApi(new Client, $config);

    $account = new BankAccountIbanPost([
        'iban'          => 'ES9121000418450200051332',
        'owner_name'    => 'Joe Bloggs',
        'owner_address' => new Address([
            'address_line1' => 'Address line 1',
            'address_line2' => 'Address line 2',
            'city'          => 'City',
            'region'        => 'string',
            'postal_code'   => '11222',
            'country'       => 'FR'
        ])
    ]);

    $accountResponse = $usersApi->usersPostBankAccountIban(
        $userResponse->getId(),
        $account
    );

    // Guardar el $accountResponse->getId();
    /* ... */
    dump($accountResponse);
}
catch (Exception $e)
{
    // Ha habido un error creando el account
    dump($e);
}

// 7. CREAR UN PAYOUT
try
{
    $payOutsApi = new PayOutsBankwireApi(new Client, $config);

    $payOut = new PayOutBankWirePost([
        'author_id' => $userResponse->getId(),
        'bank_account_id' => $accountResponse->getId(),
        'debited_wallet_id' => $walletResponse->getId(),
        'debited_funds' => new Money([
            'amount' => 100,
            'currency' => 'EUR'
        ]),
        'fees' => new Money([
            'amount' => 0,
            'currency' => 'EUR'
        ])
    ]);

    $payOutResponse = $payOutsApi->payOutsBankwirePost($payOut);

    // Guardar el 
    /* ... */
    dump($payOutResponse);
}
catch (Exception $e)
{
    // Ha habido un error creando el payout
    dump($e);
}