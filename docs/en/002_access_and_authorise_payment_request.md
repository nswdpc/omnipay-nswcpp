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

```php
use Omnipay\Omnipay;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\ParameterStorage;
use Omnipay\NSWGOVCPP\AccessTokenRequestException;
use Omnipay\NSWGOVCPP\CompleteAccessTokenRequestException;


try {

    $config = [
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'accessTokenUrl' => 'https://auth.example.com/accesstoken',
        'requestPaymentUrl' => 'https://payments.example.com/paymentrequest',
        'gatewayUrl' => 'https://gateway.example.com/'
    ];
    $parameters = ParameterStorage::setAll($config);

    // Setup CPP payment gateway - it will draw the parameters from ParameterStorage automatically
    $gateway = Omnipay::create( Gateway::class );

    // Purchase data
    $payload = [
        'productDescription' => 'Widget',// mandatory
        'amount' => 90,// mandatory
        'callingSystem' => 'YOUR_CALLING_SYSTEM',
        'referenceNumber' => 'YOUR_PAYMENT_REFERENCE',// optional
        'agencyTransactionId' => 'YOUR_TRANSACTON_ID',
        'subAgencyCode' => 'e.g on behalf of agency code',//optional
        'discounts' => [
            [
                'amount': 10,// $
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
                "amount": 20,
                "agencyCode": "SUB_AGENCY_CODE 3"
            ]
        ]
    ];

    /**
     * Get an @var AccessTokenResponse
     * If the access token has not expired, the current access token for the session will be returned
     * If the access token is received an AccessTokenRequestException will be thrown
     * Validation of the token occurs at `completeAuthorize`
     */
    $accessTokenResponse = $gateway->authorize()->send();
    $accessToken = $accessTokenResponse->getAccessToken();

    /**
     * Complete the access token validation
     * When the access token is validated, the payer will be immediately redirected to the gatewayUrl
     *  - no further processing here will take place
     * If the validation or purchase request fails a CompleteAccessTokenRequestException will be thrown
     */
    $completeAccessTokenResponse = $gateway->completeAuthorize([
        'accessToken' => $accessToken,/* @var AccessToken */
        'payload' => $payload/* @var array */
    ])->send();

    /*
     * Save the data returned from the payment request
     * and link it to your payload
     * This is needed to validate the payment at the Payment Completion stage
    */
    $myApp->savePaymentReference($payload, $completeAccessTokenResponse);

    // redirect to the CPP payment handling URL
    $completeAccessTokenResponse->redirect();

} catch (AccessTokenRequestException $e) {
    // invalid access token request or response failure
} catch (CompleteAccessTokenRequestException $e) {
    // could not validate the payment request
} catch (\Exception $e) {
    // general exception handling
}
```

Read next: [Payment completion](./003_payment_competion.md)
