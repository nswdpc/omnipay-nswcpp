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

If you are using the Silverstripe CPP module provided by NSWDPC Digital, this can be done from the CPP administration area.

## Example

```php
use Omnipay\Omnipay;
use Omnipay\NSWGOVCPP\AccessTokenRequestException;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\ParameterStorage;
use Omnipay\NSWGOVCPP\RefundRequestException;
use Omnipay\NSWGOVCPP\RefundRequest;
use Omnipay\NSWGOVCPP\RefundResponse;

try {

    $refundAmount = $myApp->getRefundAmount();// mandatory
    $refundReason = $myApp->getRefundReason();// optional
    $paymentReference = $myApp->getPaymentReference();// mandatory

    $config = [
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'refundUrl' => 'https://payment.example.com/refund'
    ];
    $parameters = ParameterStorage::setAll($config);

    // Setup CPP payment gateway - it will draw the parameters from ParameterStorage automatically
    // @var Omnipay\NSWGOVCPP\Gateway
    $gateway = Omnipay::create( Gateway::class );

    // @var Omnipay\NSWGOVCPP\RefundRequest
    $refundRequest = $gateway->refund([
        'paymentReference' => $paymentReference,
        'refundAmount' => $refundAmount,
        'refundReason' => $refundReason
    ]);

    // @var Omnipay\NSWGOVCPP\RefundResponse
    $refundResponse = $refundRequest->send();

    // @var string
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
