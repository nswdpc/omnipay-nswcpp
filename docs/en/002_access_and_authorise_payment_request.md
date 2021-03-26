# Example: request an access token & send a payment request

> Prerequisites: you have an active, working CPP account with credentials

## Background notes

- The URLs given in this example are taken from the CPP documentation, verify before use
- The access token is retrieved using the OAuth2 `client_credentials` grant type
- Example [purchase payloads are available](https://documenter.getpostman.com/view/7222098/SzfCSkTn?version=latest#9e9d6f24-2d16-4e70-85b1-f4caed287805)

## Requirements

+ A `clientId` for OAuth2
+ A `clientSecret` for OAuth2
+ A URL for requesting an access token using the `clientId` and `clientSecret`
+ A payment request URL
+ A gateway URL, where the citizen will complete the payment

## Example

The following is an example process for creating a payment request and delivering a citizen to the payment gateway

See also [GatewayTest](../../tests/GatewayTest.php) for test cases.

```php
use Omnipay\Omnipay;
use Omnipay\NSWGOVCPP\AccessTokenRequestException;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\ParameterStorage;
use Omnipay\NSWGOVCPP\PurchaseRequestException;


try {

    $config = [
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'accessTokenUrl' => 'https://auth.example.com/accesstoken',
        'requestPaymentUrl' => 'https://payments.example.com/paymentrequest',
        'gatewayUrl' => 'https://gateway.example.com/'
    ];
    ParameterStorage::setAll($config);

    // Setup CPP payment gateway - it will draw the parameters from ParameterStorage automatically
    // @var Omnipay\NSWGOVCPP\Gateway
    $gateway = Omnipay::create( Gateway::class );

    // your TXN id
    $txnId = 'YOUR_TRANSACTON_ID';

    // Example payment payload for a product
    $payload = [
        'productDescription' => 'Widget',// mandatory
        'amount' => 90,// mandatory
        'callingSystem' => 'YOUR_CALLING_SYSTEM',
        'referenceNumber' => 'YOUR_PAYMENT_REFERENCE',// optional
        'agencyTransactionId' => $txnId,
        'subAgencyCode' => 'e.g on behalf of agency code',//optional
        'discounts' => [
            [
                'amount': 100,// AUD
                'code': 'DISCOUNT_CODE',
                'reference': 'DISCOUNT_REFERENCE'
            ]
            // .. another discount
        ],
        "disbursements": [
            [
                "amount": 30,
                "agencyCode": "SUB_AGENCY_CODE 1"
            ],
            [
                "amount": 30,
                "agencyCode": "SUB_AGENCY_CODE 2"
            ],
            [
                "amount": 40,
                "agencyCode": "SUB_AGENCY_CODE 3"
            ]
        ]
    ];

    // make the purchase

    // @var Omnipay\NSWGOVCPP\PurchaseRequest
    $purchaseRequest = $gateway->purchase([
        'payload' => $payload
    ]);
    // @var Omnipay\NSWGOVCPP\PurchaseResponse
    $purchaseResponse = $purchase->send();


    // @var string get the payment reference
    $paymentReference = $purchaseResponse->getPaymentReference();
    // @var boolean if CPP considers this a duplicate (previous Agency TXN ID) this will be true
    $duplicate = $purchaseResponse->isDuplicate();

    // handle/validate/save the payment reference
    $myApp->savePaymentReference($txnId, $paymentReference, $duplicate);

    // @var string the URL the citizen will complete payments at
    $redirectUrl = $purchaseResponse->getRedirectUrl();

    // do the redirect using a response or with the $redirectUrl
    // @var Symfony\Component\HttpFoundation\Response
    $response = $purchaseResponse->redirect();

    // after successful payment, citizen will be redirected to a completion URL

} catch (AccessTokenRequestException $e) {
    // invalid access token request or response failure
} catch (PurchaseRequestException $e) {
    // could not validate the payment request
} catch (\Exception $e) {
    // general exception handling
}
```

Read next: [Payment completion](./003_payment_competion.md)
