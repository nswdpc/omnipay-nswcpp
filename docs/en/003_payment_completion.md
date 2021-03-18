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

In this example, your application has accepted the payment completion HTTP request and is verifying the incoming data.

The customer is awaiting the results of this verification at the gateway.

Upon returning a `200 OK`, the customer will be redirected to your configured payment completion page.

```php

use Omnipay\Omnipay;
use Omnipay\NSWGOVCPP\CompletePurchaseResponse;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\ParameterStorage;
use Omnipay\NSWGOVCPP\CompletePurchaseRequestException;
use Omnipay\NSWGOVCPP\UnprocessableEntityException;

try {

    $callback = function(CompletePurchaseResponse $response) {
        /**
         * process payment completion in your app
         * the callback can:
         * return true on success (resulting in a 200)
         * return false on failure (resulting in a 503)
         * or throw an UnprocessableEntityException to return a HTTP 422 to the CPP
         */
    };

    // the CPP will POST a JWT to your controller
    $jwt = $myApp->getToken();

    $config = [
        'jwtPublicKey' => 'jwt-public-key'
    ];

    $parameters = ParameterStorage::setAll($config);

    // Setup CPP payment gateway - it will draw the parameters from ParameterStorage automatically
    $gateway = Omnipay::create( Gateway::class );

    /**
     * Return a CompletePurchaseResponse
     * @throws CompletePurchaseRequestException if the JWT was not validated
     */
    $completePurchaseResponse = $gateway->completePurchase([
        'jwt' => $jwt
    ])->send();

    /**
     * Handle payment completion
     * Your app should provide a callback taking the CompletePurchaseResponse instance as the parameter
     * You app can call $response->getData() to get all data sent from CPP
     * complete() will send the correct HTTP headers for CPP to complete/fail payment and redirect your site
     */
    $completePurchaseResponse->complete( $callback );

    /**
     * @var Symfony\Component\HttpFoundation\Response;
     */
    $response = $complete->send();

} catch (CompletePurchaseRequestException $e) {
    // the JWT is not valid
} catch (\Exception $e) {
    // general exception handling
    // if you have completely failed, send a HTTP 422
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422
}
```
