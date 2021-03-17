# Payment completion

Upon successful purchase by the payer at the CPP gateway, a response will be sent to your payment completion controller.

This occurs in a separate process to authorisation at any time after you have redirect the payer to the CPP Gateway.

It requires the payment reference data to be available in your system.

## CPP retry handling

[Reference](https://cpp-info-hub.service.nsw.gov.au/getstarted/#retry-mechanisms)

1. CPP will retry if there is no response or if the agency send any 5xx http code.
1. CPP will wait for 90 seconds before retrying
1. CPP will retry 6 times
1. CPP will not retry if the endpoint gives a 422 code

## Payload
See [CPPAgency Services](https://documenter.getpostman.com/view/7222098/SzfCSkTn?version=latest#b1fe8def-5844-4bc4-8699-16e021ae805e) for a complete field example of payment data returned based on the payment type.

## Example

In this example, your application has accepted the request and is verifying the incoming data.

```php
use Omnipay\Omnipay;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\Exception\CompletePurchaseRequestException;

try {

    /**
     * Your application provides the following
     * @var array $payload payload data received
     * @var string $token JWT sent so agency (you) can verify request
     */

    $token = $myApp->getToken();// the JWT

    // Setup CPP payment gateway
    $gateway = Omnipay::create( Gateway::class );

    // This process only validates the JWT
    $gateway->initialize([
        'jwtSecret' => $jwtSecret
    ]);

    /**
     * Return a CompletePurchaseResponse
     * @throws CompletePurchaseRequestException if the JWT was not validated
     */
    $completePurchaseResponse = $gateway->completePurchase([
        'token' => $token
    ])->send();

    /**
     * Handle payment completion
     * Your app should provide a callback taking the CompletePurchaseResponse instance as the parameter
     * You app can call $response->getData() to get all data sent from CPP
     * complete() will send the correct HTTP headers for CPP to complete/fail payment and redirect your site
     */
    $completePurchaseResponse->complete( $callback );

} catch (CompletePurchaseRequestException $e) {
    // the JWT is not valid
} catch (\Exception $e) {
    // general exception handling
    // if you have completely failed, send a HTTP 422
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422
}
```
