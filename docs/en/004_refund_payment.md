# Refund a payment

At some point in time, for various reasons, you may need to refund a payment or part of it.

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
use Omnipay\NSWGOVCPP\AccessToken;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\ParameterStorage;
use Omnipay\NSWGOVCPP\AccessTokenRequestException;
use Omnipay\NSWGOVCPP\RefundRequestException;

try {

    $refundAmount = $myApp->getRefundAmount();// mandatory
    $refundReason = $myApp->getRefundReason();// optional

    $config = [
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'refundUrl' => 'https://auth.example.com/accesstoken'
    ];
    $parameters = ParameterStorage::setAll($config);

    // Setup CPP payment gateway
    $gateway = Omnipay::create( Gateway::class );

    // get an access token
    $accessTokenRequest = $this->gateway->authorize();
    $accessTokenResponse = $accessTokenRequest->send();

    /**
     * @var AccessToken
     */
    $accessToken = $accessTokenResponse->getAccessToken();

    /**
     * Send refund request
     * If the refund request fails a RefundRequestException will be thrown
     * If not the $refundResponse represents the response to a successful refund request
     */
    $refundRequest = $gateway->refund([
        'accessToken' => $accessToken,/* @var AccessToken */
        'refundAmount' => $refundAmount,
        'refundReason' => $refundReason
    ]);

    $refundResponse = $refundRequest->send();

    /**
     * @var string
     */
    $refundReference = $refundResponse->getRefundReference();

    // handle successful refund response using the reference

} catch (AccessTokenRequestException $e) {
    // invalid access token request or response failure
} catch (RefundRequestException $e) {
    // invalid refund (maybe wrong refundReference) or response failure
    // handle refund failure here
} catch (\Exception $e) {
    // general exception handling
}
```
