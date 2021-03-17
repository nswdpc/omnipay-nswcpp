# Refund a payment

At some point in time, for various reasons, you may need to refund a payment or part of it. 4

## Requirements

The validation requirements for refunding a payment are:

+ The payment should have a valid payment reference
+ The payment should be a payment done for that agency
+ The payment should be completed
+ The payment should not have been refunded or voided earlier
+ The amount being refunded should be less than the amount collected, excluding surcharge and surcharge-gst

Refunds should be actioned from a secure backend administration area in your application, the scope of which is not covered by this module.

If you are using the Silverstripe CPP module provided by NSWDPC Digital, this can be done from the Payments administration area.

## Example

```php
use Omnipay\Omnipay;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\Exception\AccessTokenRequestException;
use Omnipay\NSWGOVCPP\Exception\RefundRequestException;

try {

    // Setup CPP payment gateway
    $gateway = Omnipay::create( Gateway::class );

    // You provide your client-id and client-secret
    $gateway->initialize([
        'clientId' => $clientId,
        'clientSecret' => $clientSecret
    ]);

    /**
     * Get an @var AccessTokenResponse
     * If the access token has not expired, the current access token for the session will be returned
     * If the access token is invalid or not received an AccessTokenRequestException will be thrown
     */
    $accessToken = $gateway->authorize([
        // provide the endpointUrl for access token requests
        'endpointUrl' => 'https://api-psm.g.testservicensw.net/v1/identity/oauth/client-credentials/token'
    ])->send();

    /**
     * Send refund request
     * If the refund request fails a RefundRequestException will be thrown
     * If not the $refundResponse represents the response to a successful refund request
     */
    $refundResponse = $gateway->refund([
        'endpointUrl' => 'https://api-psm.g.testservicensw.net/cpp-digital/api/{paymentReference}/refund',
        'accessToken' => $accessToken /* @var AccessTokenResponse */
        'paymentReference' => $paymentReference,
        'amount' => $amount, // mandatory
        'refundReason' => 'reason string' // optional
    ]
    )->send();

    // handle successful refund response

} catch (AccessTokenRequestException $e) {
    // invalid access token request or response failure
} catch (RefundRequestException $e) {
    // invalid refund (maybe wrong refundReference) or response failure
    // handle refund failure here
} catch (\Exception $e) {
    // general exception handling
}
```
